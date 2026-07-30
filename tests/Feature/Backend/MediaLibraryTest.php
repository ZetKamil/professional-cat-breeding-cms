<?php

use App\Models\Animal;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->seed(\Database\Seeders\RoleSeeder::class);
});

test('admin can upload single and multiple media images with metadata', function () {
    $admin = User::factory()->create(['role_id' => 1]); // admin
    $this->actingAs($admin);

    $file1 = UploadedFile::fake()->image('cat1.jpg', 600, 600);
    $file2 = UploadedFile::fake()->image('cat2.jpg', 600, 600);

    $response = $this->post(route('backend.media.store'), [
        'uploads' => [$file1, $file2],
        'title' => 'Luxury Cat Photo',
        'alt_text' => 'A gorgeous Bengal cat',
        'caption' => 'Bengal sitting gracefully',
        'copyright' => '© 2026 ZetKamil',
        'sort_order' => 1,
        'is_featured' => 0,
        'parent_type' => '',
        'parent_id' => '',
    ]);

    $response->assertRedirect(route('backend.media.index'));

    expect(Media::count())->toBe(2);

    $media1 = Media::orderBy('id')->first();
    expect($media1->title)->toBe('Luxury Cat Photo')
        ->and($media1->alt_text)->toBe('A gorgeous Bengal cat')
        ->and($media1->caption)->toBe('Bengal sitting gracefully')
        ->and($media1->copyright)->toBe('© 2026 ZetKamil')
        ->and($media1->mediable_type)->toBeNull()
        ->and($media1->mediable_id)->toBeNull();

    Storage::disk('public')->assertExists($media1->path());
});

test('admin can view media library in grid and list views with filters and api endpoint', function () {
    $admin = User::factory()->create(['role_id' => 1]);
    $this->actingAs($admin);

    Media::create([
        'disk' => 'public',
        'directory' => 'media/library',
        'filename' => 'sample.jpg',
        'mime_type' => 'image/jpeg',
        'size' => 1024,
        'title' => 'Sample Image',
        'alt_text' => 'Sample alt',
    ]);

    $responseGrid = $this->get(route('backend.media.index', ['view' => 'grid']));
    $responseGrid->assertOk()
        ->assertSee('Sample Image');

    $responseList = $this->get(route('backend.media.index', ['view' => 'list']));
    $responseList->assertOk()
        ->assertSee('Sample Image')
        ->assertSee('1 KB');

    $responseApi = $this->getJson(route('backend.media.api', ['q' => 'Sample']));
    $responseApi->assertOk()
        ->assertJsonFragment(['filename' => 'sample.jpg']);
});

test('admin can replace an existing media image while updating metadata', function () {
    $admin = User::factory()->create(['role_id' => 1]);
    $this->actingAs($admin);

    $oldFile = UploadedFile::fake()->image('old.jpg');
    $storedPath = $oldFile->store('media/library', 'public');

    $media = Media::create([
        'disk' => 'public',
        'directory' => 'media/library',
        'filename' => basename($storedPath),
        'mime_type' => 'image/jpeg',
        'size' => 2048,
        'title' => 'Old Title',
        'alt_text' => 'Old Alt',
    ]);

    $newFile = UploadedFile::fake()->image('new.jpg', 800, 800);

    $response = $this->patch(route('backend.media.update', $media), [
        'upload' => $newFile,
        'title' => 'New Title',
        'alt_text' => 'New Alt',
        'caption' => 'New Caption',
        'copyright' => '© New',
        'sort_order' => 5,
        'is_featured' => 1,
    ]);

    $response->assertRedirect(route('backend.media.edit', $media));

    $media->refresh();

    expect($media->title)->toBe('New Title')
        ->and($media->alt_text)->toBe('New Alt')
        ->and($media->caption)->toBe('New Caption')
        ->and($media->copyright)->toBe('© New')
        ->and($media->sort_order)->toBe(5)
        ->and($media->is_featured)->toBeTrue();

    Storage::disk('public')->assertExists($media->path());
});

test('admin can delete media and physical file is removed from disk', function () {
    $admin = User::factory()->create(['role_id' => 1]);
    $this->actingAs($admin);

    $file = UploadedFile::fake()->image('todelete.jpg');
    $storedPath = $file->store('media/library', 'public');

    $media = Media::create([
        'disk' => 'public',
        'directory' => 'media/library',
        'filename' => basename($storedPath),
        'mime_type' => 'image/jpeg',
        'size' => 500,
    ]);

    $path = $media->path();
    Storage::disk('public')->assertExists($path);

    $response = $this->delete(route('backend.media.destroy', $media));
    $response->assertRedirect(route('backend.media.index'));

    expect(Media::find($media->id))->toBeNull();
    Storage::disk('public')->assertMissing($path);
});
