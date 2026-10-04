<?php

namespace App\Services;

use App\Events\PostCreated;
use App\Events\PostUpdated;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PostService
{
    protected MediaService $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function create(array $data): Post
    {
        return DB::transaction(function () use ($data) {

            $post = Post::create([
                'user_id'          => $data['user_id'] ?? null,
                'title'            => $data['title'],
                'slug'             => $data['slug'],
                'excerpt'          => $data['excerpt'] ?? null,
                'body'             => $data['body'] ?? '',
                'sections'         => $data['sections'] ?? null,
                'meta_title'       => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'is_published'     => $data['is_published'],
                'published_at'     => $data['published_at'] ?? null,
            ]);

            $post->categories()->sync($data['categories'] ?? []);

            // Sync featured animals (for the "cats below the article" strip)
            $animalIds = $data['featured_animal_ids'] ?? [];
            $this->syncAnimals($post, $animalIds);

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $this->mediaService->upload(
                    $post,
                    $data['image'],
                    'posts'
                );
            }

            PostCreated::dispatch($post);

            return $post;
        });
    }

    public function update(Post $post, array $data): Post
    {
        return DB::transaction(function () use ($post, $data) {

            $post->update([
                'user_id'          => $data['user_id'] ?? null,
                'title'            => $data['title'],
                'slug'             => $data['slug'],
                'excerpt'          => $data['excerpt'] ?? null,
                'body'             => $data['body'] ?? '',
                'sections'         => array_key_exists('sections', $data) ? $data['sections'] : $post->sections,
                'meta_title'       => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'is_published'     => $data['is_published'],
                'published_at'     => $data['published_at'] ?? null,
            ]);

            $post->categories()->sync($data['categories'] ?? []);

            // Sync featured animals
            $animalIds = $data['featured_animal_ids'] ?? [];
            $this->syncAnimals($post, $animalIds);

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $this->mediaService->replace(
                    $post,
                    $data['image'],
                    'posts'
                );
            }

            PostUpdated::dispatch($post);

            return $post;
        });
    }

    /**
     * Sync the animal_post pivot with sort_order.
     *
     * @param  array<int, string>  $animalIds  ULIDs of selected animals
     */
    private function syncAnimals(Post $post, array $animalIds): void
    {
        $syncData = [];
        foreach (array_values($animalIds) as $i => $id) {
            $syncData[$id] = ['sort_order' => $i];
        }
        $post->animals()->sync($syncData);
    }
}
