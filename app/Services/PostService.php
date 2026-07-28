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
                'user_id' => $data['user_id'] ?? null,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'excerpt' => $data['excerpt'] ?? null,
                'body' => $data['body'],
                'is_published' => $data['is_published'],
                'published_at' => $data['published_at'] ?? null,
            ]);

            $post->categories()->sync($data['categories'] ?? []);

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
                'user_id' => $data['user_id'] ?? null,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'excerpt' => $data['excerpt'] ?? null,
                'body' => $data['body'],
                'is_published' => $data['is_published'],
                'published_at' => $data['published_at'] ?? null,
            ]);

            $post->categories()->sync($data['categories'] ?? []);

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
}
