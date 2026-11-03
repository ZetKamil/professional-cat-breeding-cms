<?php

namespace Tests\Feature\Backend;

use App\Models\Animal;
use App\Models\Media;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnimalGalleryReorderTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $this->admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);
    }

    public function test_admin_can_reorder_gallery_media_via_patch_endpoint(): void
    {
        $animal = Animal::factory()->create([
            'name' => 'Baron Testowy',
        ]);

        $media1 = Media::create([
            'disk' => 'public',
            'directory' => 'animals',
            'filename' => 'photo1.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'sort_order' => 1,
            'is_featured' => false,
            'mediable_type' => Animal::class,
            'mediable_id' => $animal->id,
        ]);

        $media2 = Media::create([
            'disk' => 'public',
            'directory' => 'animals',
            'filename' => 'photo2.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'sort_order' => 2,
            'is_featured' => false,
            'mediable_type' => Animal::class,
            'mediable_id' => $animal->id,
        ]);

        $media3 = Media::create([
            'disk' => 'public',
            'directory' => 'animals',
            'filename' => 'photo3.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'sort_order' => 3,
            'is_featured' => false,
            'mediable_type' => Animal::class,
            'mediable_id' => $animal->id,
        ]);

        // Reorder: 3, 1, 2
        $response = $this->actingAs($this->admin)->patchJson(
            route('backend.animals.media.reorder', $animal),
            [
                'media_ids' => [$media3->id, $media1->id, $media2->id],
            ]
        );

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertSame(1, $media3->fresh()->sort_order);
        $this->assertSame(2, $media1->fresh()->sort_order);
        $this->assertSame(3, $media2->fresh()->sort_order);

        // Verify public gallery ordering reflects the new sort_order
        $orderedIds = $animal->gallery()->pluck('id')->all();
        $this->assertSame([$media3->id, $media1->id, $media2->id], $orderedIds);
    }

    public function test_cannot_reorder_media_belonging_to_another_animal(): void
    {
        $animal1 = Animal::factory()->create(['name' => 'Kot 1']);
        $animal2 = Animal::factory()->create(['name' => 'Kot 2']);

        $media1 = Media::create([
            'disk' => 'public',
            'directory' => 'animals',
            'filename' => 'cat1_photo.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'sort_order' => 1,
            'is_featured' => false,
            'mediable_type' => Animal::class,
            'mediable_id' => $animal1->id,
        ]);

        $media2 = Media::create([
            'disk' => 'public',
            'directory' => 'animals',
            'filename' => 'cat2_photo.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'sort_order' => 99,
            'is_featured' => false,
            'mediable_type' => Animal::class,
            'mediable_id' => $animal2->id,
        ]);

        // Attempting to pass media2 in animal1's reorder call must not affect media2's sort_order
        $response = $this->actingAs($this->admin)->patchJson(
            route('backend.animals.media.reorder', $animal1),
            [
                'media_ids' => [$media2->id, $media1->id],
            ]
        );

        $response->assertOk();

        // media2 still belongs to animal2 and its sort_order must remain untouched (99)
        $this->assertSame(99, $media2->fresh()->sort_order);
        // media1 was the second in the array and belongs to animal1, so it gets updated to 2
        $this->assertSame(2, $media1->fresh()->sort_order);
    }

    public function test_guest_cannot_reorder_gallery_media(): void
    {
        $animal = Animal::factory()->create();

        $response = $this->patchJson(
            route('backend.animals.media.reorder', $animal),
            ['media_ids' => [1]]
        );

        $response->assertUnauthorized();
    }

    public function test_non_admin_cannot_reorder_gallery_media(): void
    {
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $regularUser = User::factory()->create(['role_id' => $userRole->id]);
        $animal = Animal::factory()->create();

        $response = $this->actingAs($regularUser)->patchJson(
            route('backend.animals.media.reorder', $animal),
            ['media_ids' => [1]]
        );

        $response->assertForbidden();
    }

    public function test_admin_edit_form_renders_gallery_sortable_container(): void
    {
        $animal = Animal::factory()->create(['name' => 'Sortable Baron']);

        Media::create([
            'disk' => 'public',
            'directory' => 'animals',
            'filename' => 'sort1.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'sort_order' => 1,
            'is_featured' => true,
            'mediable_type' => Animal::class,
            'mediable_id' => $animal->id,
        ]);

        Media::create([
            'disk' => 'public',
            'directory' => 'animals',
            'filename' => 'sort2.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'sort_order' => 2,
            'is_featured' => false,
            'mediable_type' => Animal::class,
            'mediable_id' => $animal->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('backend.animals.edit', $animal));

        $response->assertOk();
        $response->assertSee('id="animal-gallery-sortable"', false);
        $response->assertSee('gallery-sortable-item', false);
        $response->assertSee('#1');
        $response->assertSee('#2');
    }
}
