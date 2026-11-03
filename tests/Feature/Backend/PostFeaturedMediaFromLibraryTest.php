<?php

declare(strict_types=1);

namespace Tests\Feature\Backend;

use App\Models\Animal;
use App\Models\Media;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostFeaturedMediaFromLibraryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_post_with_featured_media_from_library(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $libraryMedia = Media::create([
            'disk' => 'public',
            'directory' => 'media/library',
            'filename' => 'cat_banner.jpg',
            'title' => 'Cat Banner',
            'mediable_type' => null,
            'mediable_id' => null,
            'is_featured' => false,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('backend.posts.store'), [
                'title' => 'Nowy wspanialy wpis o kotach',
                'slug' => 'nowy-wspanialy-wpis-o-kotach',
                'body' => 'Tresc artykulu z odpowiednia dlugoscia znakow...',
                'is_published' => 1,
                'featured_media_id' => $libraryMedia->id,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('backend.posts.index'));

        $post = Post::where('slug', 'nowy-wspanialy-wpis-o-kotach')->first();
        expect($post)->not->toBeNull();
        expect($post->media)->not->toBeNull();
        expect($post->media->filename)->toBe('cat_banner.jpg');
        expect($post->media->is_featured)->toBeTrue();
    }

    public function test_admin_can_update_post_to_use_library_media_as_cover(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $post = Post::factory()->create([
            'user_id' => $admin->id,
            'title' => 'Stary tytul',
            'slug' => 'stary-tytul',
            'body' => 'Dotychczasowa tresc posta blogowego...',
            'is_published' => 1,
        ]);

        $libraryMedia = Media::create([
            'disk' => 'public',
            'directory' => 'media/library',
            'filename' => 'nowa_okladka.jpg',
            'mediable_type' => null,
            'mediable_id' => null,
            'is_featured' => false,
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('backend.posts.update', $post), [
                'title' => 'Zaktualizowany post z nowa okladka',
                'slug' => 'zaktualizowany-post-z-nowa-okladka',
                'body' => 'Zaktualizowana tresc posta blogowego...',
                'is_published' => 1,
                'featured_media_id' => $libraryMedia->id,
            ]);

        $response->assertSessionHasNoErrors();

        $post->refresh();
        $post->load('media');

        expect($post->media)->not->toBeNull();
        expect($post->media->filename)->toBe('nowa_okladka.jpg');
        expect($post->media->is_featured)->toBeTrue();
    }

    public function test_selecting_media_belonging_to_animal_replicates_it_so_animal_keeps_it(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $animal = Animal::factory()->create(['name' => 'Baron']);
        $animalMedia = Media::create([
            'disk' => 'public',
            'directory' => 'media/animals',
            'filename' => 'baron_photo.jpg',
            'mediable_type' => Animal::class,
            'mediable_id' => $animal->id,
            'is_featured' => true,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('backend.posts.store'), [
                'title' => 'Historia Barona w nowym wpisie',
                'slug' => 'historia-barona-w-nowym-wpisie',
                'body' => 'Tresc posta z odpowiednia liczba znakow...',
                'is_published' => 1,
                'featured_media_id' => $animalMedia->id,
            ]);

        $response->assertSessionHasNoErrors();

        // Animal must still have its media intact
        $animal->refresh();
        $animal->load('media');
        expect($animal->media)->not->toBeNull();
        expect($animal->media->id)->toBe($animalMedia->id);

        // Post must also have its own featured media pointing to same file
        $post = Post::where('slug', 'historia-barona-w-nowym-wpisie')->first();
        expect($post->media)->not->toBeNull();
        expect($post->media->filename)->toBe('baron_photo.jpg');
        expect($post->media->is_featured)->toBeTrue();
        expect($post->media->id)->not->toBe($animalMedia->id); // Cloned
    }
}
