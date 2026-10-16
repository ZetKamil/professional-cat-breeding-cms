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

class AnimalImageCropTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_animal_with_cropped_image_base64_payload(): void
    {
        Storage::fake('public');

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $animal = Animal::factory()->create([
            'name' => 'Daisy Crop Test',
            'slug' => 'daisy-crop-test',
        ]);

        // Generate a 1:1 test image as base64 data URI (simulating Cropper.js output)
        $fakeImage = UploadedFile::fake()->image('daisy_cropped.jpg', 600, 600);
        $base64DataUri = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($fakeImage->getPathname()));

        $response = $this->actingAs($admin)
            ->put(route('backend.animals.update', $animal), [
                'name' => $animal->name,
                'slug' => $animal->slug,
                'breed' => $animal->breed,
                'gender' => $animal->gender->value,
                'status' => $animal->status->value,
                'type' => $animal->type->value,
                'is_featured' => 0,
                'is_published' => 1,
                'cropped_image' => $base64DataUri,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('backend.animals.edit', $animal));

        $animal->refresh();
        $animal->load('media');

        expect($animal->media)->not->toBeNull()
            ->and($animal->media->is_featured)->toBeTrue();
    }

    public function test_admin_can_create_new_animal_with_cropped_image(): void
    {
        Storage::fake('public');

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $fakeImage = UploadedFile::fake()->image('new_kitten_cropped.jpg', 800, 800);
        $base64DataUri = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($fakeImage->getPathname()));

        $response = $this->actingAs($admin)
            ->post(route('backend.animals.store'), [
                'name' => 'Nowy Maluch',
                'slug' => 'nowy-maluch',
                'breed' => 'Kot Syjamski',
                'gender' => 'male',
                'status' => 'available',
                'type' => 'cat',
                'is_featured' => 1,
                'is_published' => 1,
                'cropped_image' => $base64DataUri,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('backend.animals.index'));

        $created = Animal::where('slug', 'nowy-maluch')->first();
        expect($created)->not->toBeNull();
        expect($created->media)->not->toBeNull();
    }
}
