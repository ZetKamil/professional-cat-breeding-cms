<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use App\Models\Animal;
use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Package3DomainAndImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_seo_articles_parses_nested_yaml_and_handles_soft_deleted_posts(): void
    {
        Storage::fake('public');

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        User::factory()->create(['role_id' => $adminRole->id]);
        Category::factory()->create(['name' => 'Test Kategoria', 'slug' => 'test-kategoria']);

        $folderName = '999-audit-test-article';
        $sourceDir = base_path("BLOG/{$folderName}");
        $publicTargetDir = public_path("storage/blog/{$folderName}");

        File::ensureDirectoryExists($sourceDir);
        File::put($sourceDir.'/01_article.md', "# Tytuł z Markdown\n\nPrzykładowy akapit z ![Alt obrazu](02_hero.jpg).");
        File::put($sourceDir.'/02_hero.jpg', 'fake-jpg-payload');
        File::put($sourceDir.'/04_content_metadata.yml', <<<'YAML'
title: "Artykuł Audytowy YAML"
slug: "audit-test-article"
publication_date: "2026-08-02"
publication_time: "10:00"
hero_image:
  filename: "02_hero.jpg"
  alt: "Bengalski kociak testowy"
meta:
  title: "Meta Tytuł Zagnieżdżony"
  description: "Meta opis zagnieżdżony z sekcji YAML"
categories:
  - "Test Kategoria"
YAML);

        try {
            $this->artisan('seo:import-articles', ['--folder' => $folderName])
                ->assertExitCode(0);

            $post = Post::with(['categories', 'media'])->where('slug', 'audit-test-article')->first();
            $this->assertNotNull($post);
            $this->assertSame('Artykuł Audytowy YAML', $post->title);
            $this->assertSame('Meta Tytuł Zagnieżdżony', $post->meta_title);
            $this->assertSame('Meta opis zagnieżdżony z sekcji YAML', $post->meta_description);
            $this->assertSame(['test-kategoria'], $post->categories->pluck('slug')->all());
            $this->assertNotNull($post->media);
            $this->assertSame("blog/{$folderName}", $post->media->directory);
            $this->assertSame('02_hero.jpg', $post->media->filename);
            $this->assertSame('Bengalski kociak testowy', $post->media->alt_text);

            // Soft-delete post and verify normal run skips cleanly without UNIQUE violation
            $post->delete();
            $this->artisan('seo:import-articles', ['--folder' => $folderName])
                ->assertExitCode(0);
            $this->assertSoftDeleted('posts', ['id' => $post->id]);

            // With --force-sync it restores and updates cleanly
            $this->artisan('seo:import-articles', ['--folder' => $folderName, '--force-sync' => true])
                ->assertExitCode(0);
            $this->assertNull($post->fresh()->deleted_at);
        } finally {
            File::deleteDirectory($sourceDir);
            File::deleteDirectory($publicTargetDir);
        }
    }

    public function test_setting_get_does_not_cache_caller_default_and_preserves_booleans(): void
    {
        Cache::forget('app_setting.audit_missing_key');
        Cache::forget('app_setting.audit_bool_false');
        Cache::forget('app_setting.audit_bool_true');

        $this->assertSame('default-one', Setting::get('audit_missing_key', 'default-one'));
        $this->assertSame('default-two', Setting::get('audit_missing_key', 'default-two'));

        Setting::set('audit_bool_false', false);
        Setting::set('audit_bool_true', true);

        $this->assertFalse(Setting::get('audit_bool_false', true));
        $this->assertTrue(Setting::get('audit_bool_true', false));
    }

    public function test_animal_force_delete_cleans_polymorphic_media_and_post_pivot(): void
    {
        Storage::fake('public');

        $animal = Animal::factory()->create([
            'name' => 'Audit Delete Cat',
            'slug' => 'audit-delete-cat',
            'status' => AnimalStatus::Available,
            'gender' => AnimalGender::Male,
            'type' => AnimalType::Cat,
        ]);

        $file = UploadedFile::fake()->image('cat-photo.jpg', 400, 400);
        $storedPath = $file->store('media/animals', 'public');

        $media = Media::create([
            'disk' => 'public',
            'directory' => 'media/animals',
            'filename' => basename($storedPath),
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'mediable_type' => Animal::class,
            'mediable_id' => $animal->id,
            'is_featured' => true,
        ]);

        $post = Post::factory()->create();
        $post->animals()->attach($animal->id, ['sort_order' => 0]);

        $animal->forceDelete();

        Storage::disk('public')->assertMissing($storedPath);
        $this->assertDatabaseMissing('media', ['id' => $media->id]);
        $this->assertSame(0, DB::table('animal_post')->where('animal_id', $animal->id)->count());
    }

    public function test_array_query_parameters_on_koty_and_media_api_return_200(): void
    {
        $this->get('/koty?q[]=test&breed[]=bengal&status[]=available&gender[]=male')
            ->assertOk();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id, 'is_active' => true]);

        $this->actingAs($admin)
            ->getJson(route('backend.media.api', ['q' => ['test'], 'type' => ['animal']]))
            ->assertOk();
    }
}
