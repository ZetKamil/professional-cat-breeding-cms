<?php

declare(strict_types=1);

namespace Tests\Feature\Backend;

use App\Actions\Animals\UpdateAnimalAction;
use App\Models\Animal;
use App\Models\Media;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AnimalSetFeaturedMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_set_gallery_image_as_featured_main_photo(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $animal = Animal::factory()->create(['name' => 'Barsik']);

        // Main photo
        $mainMedia = Media::create([
            'disk' => 'public',
            'directory' => 'media/animals',
            'filename' => 'main_barsik.jpg',
            'mediable_type' => Animal::class,
            'mediable_id' => $animal->id,
            'is_featured' => true,
        ]);

        // Gallery photo
        $galleryMedia = Media::create([
            'disk' => 'public',
            'directory' => 'media/animals',
            'filename' => 'gallery_barsik.jpg',
            'mediable_type' => Animal::class,
            'mediable_id' => $animal->id,
            'is_featured' => false,
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('backend.animals.media.set-featured', [$animal, $galleryMedia]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $mainMedia->refresh();
        $galleryMedia->refresh();

        expect($mainMedia->is_featured)->toBeFalse()
            ->and($galleryMedia->is_featured)->toBeTrue();

        $animal->refresh();
        $animal->load('media');
        expect($animal->media->id)->toBe($galleryMedia->id);
    }

    public function test_cannot_set_media_from_another_animal_as_featured(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $animal1 = Animal::factory()->create(['name' => 'Kot 1']);
        $animal2 = Animal::factory()->create(['name' => 'Kot 2']);

        $mediaOfAnimal2 = Media::create([
            'disk' => 'public',
            'directory' => 'media/animals',
            'filename' => 'kot2.jpg',
            'mediable_type' => Animal::class,
            'mediable_id' => $animal2->id,
            'is_featured' => false,
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('backend.animals.media.set-featured', [$animal1, $mediaOfAnimal2]));

        $response->assertStatus(404);
    }

    public function test_uploading_new_main_image_preserves_previous_image_in_gallery(): void
    {
        Storage::fake('public');

        $animal = Animal::factory()->create(['name' => 'Luki']);

        $oldMedia = Media::create([
            'disk' => 'public',
            'directory' => 'media/animals',
            'filename' => 'old_luki.jpg',
            'mediable_type' => Animal::class,
            'mediable_id' => $animal->id,
            'is_featured' => true,
        ]);

        $newImage = UploadedFile::fake()->image('new_luki.jpg', 600, 600);

        /** @var UpdateAnimalAction $action */
        $action = app(UpdateAnimalAction::class);
        $updated = $action->handle($animal, [
            'name' => $animal->name,
            'slug' => $animal->slug,
            'breed' => $animal->breed,
            'gender' => $animal->gender->value,
            'status' => $animal->status->value,
            'type' => $animal->type->value,
            'is_featured' => 0,
            'is_published' => 1,
            'image' => $newImage,
        ]);

        $oldMedia->refresh();
        expect($oldMedia->exists)->toBeTrue()
            ->and($oldMedia->is_featured)->toBeFalse();

        $updated->refresh();
        $updated->load('media', 'gallery');

        expect($updated->media->id)->not->toBe($oldMedia->id)
            ->and($updated->media->is_featured)->toBeTrue()
            ->and($updated->gallery->pluck('id'))->toContain($oldMedia->id);
    }
}
