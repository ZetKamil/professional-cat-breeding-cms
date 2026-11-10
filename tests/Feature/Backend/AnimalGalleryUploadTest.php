<?php

declare(strict_types=1);

namespace Tests\Feature\Backend;

use App\Models\Animal;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AnimalGalleryUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_multiple_gallery_images_with_automatic_optimization(): void
    {
        Storage::fake('public');

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $animal = Animal::factory()->create([
            'name' => 'Simba Test',
            'slug' => 'simba-test',
        ]);

        // 5 gallery images of various sizes and formats
        $galleryImages = [
            UploadedFile::fake()->image('cat_1.jpg', 800, 600),
            UploadedFile::fake()->image('cat_2.png', 1024, 768),
            UploadedFile::fake()->image('cat_3_large.jpg', 2500, 1875), // Oversized: will be downscaled
            UploadedFile::fake()->image('cat_4.webp', 1200, 900),
            UploadedFile::fake()->image('cat_5.jfif', 600, 600),
        ];

        $response = $this->actingAs($admin)
            ->put(route('backend.animals.update', $animal), [
                'name' => $animal->name,
                'slug' => $animal->slug,
                'breed' => $animal->breed,
                'gender' => $animal->gender->value,
                'status' => $animal->status->value,
                'type' => $animal->type->value,
                'is_featured' => $animal->is_featured ? 1 : 0,
                'is_published' => $animal->is_published ? 1 : 0,
                'gallery' => $galleryImages,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('backend.animals.edit', $animal));

        $animal->refresh();
        $animal->load('gallery');

        // All 5 images must be in the gallery
        expect($animal->gallery->count())->toBe(5);

        // Verify the oversized image was downscaled to max 1920px
        $oversizedMedia = $animal->gallery->first(fn ($m) => str_contains($m->filename, 'cat_3_large') || $m->title === 'cat_3_large');
        if ($oversizedMedia) {
            $fullPath = storage_path('app/public/' . $oversizedMedia->path());
            if (file_exists($fullPath)) {
                $info = getimagesize($fullPath);
                expect($info[0])->toBeLessThanOrEqual(1920)
                    ->and($info[1])->toBeLessThanOrEqual(1920);
            }
        }
    }

    public function test_accepts_photos_larger_than_4mb_up_to_20mb(): void
    {
        Storage::fake('public');

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $animal = Animal::factory()->create();

        // 6MB fake photo (6144 KB)
        $largePhoto = UploadedFile::fake()->create('heavy_cat.jpg', 6144, 'image/jpeg');

        $response = $this->actingAs($admin)
            ->put(route('backend.animals.update', $animal), [
                'name' => $animal->name,
                'slug' => $animal->slug,
                'breed' => $animal->breed,
                'gender' => $animal->gender->value,
                'status' => $animal->status->value,
                'type' => $animal->type->value,
                'is_featured' => $animal->is_featured ? 1 : 0,
                'is_published' => $animal->is_published ? 1 : 0,
                'image' => $largePhoto,
            ]);

        $response->assertSessionHasNoErrors();
        $animal->refresh();
        $animal->load('media');

        expect($animal->media)->not->toBeNull();
    }

    public function test_quick_gallery_upload_on_animal_show_preserves_existing_animal_fields(): void
    {
        Storage::fake('public');

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $mother = Animal::factory()->create(['name' => 'Mama', 'gender' => 'female', 'status' => 'breeding']);

        $animal = Animal::factory()->create([
            'name' => 'Leo Luxury',
            'slug' => 'leo-luxury',
            'breed' => 'Kot Bengalski',
            'color' => 'BEN n 24',
            'description' => 'Pełny opis charakteru kociaka.',
            'short_description' => 'Krótki opis testowy.',
            'price' => 3500,
            'date_of_birth' => '2026-06-01',
            'earliest_pickup_date' => '2026-09-10',
            'mother_id' => $mother->id,
            'meta_title' => 'Leo Luxury SEO',
            'meta_description' => 'Meta opis kociaka.',
        ]);

        // Show page renders dedicated gallery upload endpoint
        $showResponse = $this->actingAs($admin)->get(route('backend.animals.show', $animal));
        $showResponse->assertOk();
        $showResponse->assertSee(route('backend.animals.media.store', $animal), false);

        // Quick upload adds photos without modifying any animal attributes
        $uploadResponse = $this->actingAs($admin)
            ->from(route('backend.animals.show', $animal))
            ->post(route('backend.animals.media.store', $animal), [
                'gallery' => [
                    UploadedFile::fake()->image('quick1.jpg', 800, 600),
                    UploadedFile::fake()->image('quick2.png', 800, 600),
                ],
            ]);

        $uploadResponse->assertSessionHasNoErrors();
        $uploadResponse->assertRedirect(route('backend.animals.show', $animal));

        $animal->refresh();
        $animal->load('gallery');

        expect($animal->gallery->count())->toBe(2)
            ->and($animal->slug)->toBe('leo-luxury')
            ->and($animal->color)->toBe('BEN n 24')
            ->and($animal->description)->toBe('Pełny opis charakteru kociaka.')
            ->and($animal->short_description)->toBe('Krótki opis testowy.')
            ->and($animal->price)->toBe(3500)
            ->and($animal->date_of_birth?->toDateString())->toBe('2026-06-01')
            ->and($animal->earliest_pickup_date?->toDateString())->toBe('2026-09-10')
            ->and($animal->mother_id)->toBe($mother->id)
            ->and($animal->meta_title)->toBe('Leo Luxury SEO')
            ->and($animal->meta_description)->toBe('Meta opis kociaka.');
    }
}
