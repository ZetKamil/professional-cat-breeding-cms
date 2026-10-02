<?php

use App\Models\Animal;
use App\Models\Post;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Available cats to feature
        $bengals = Animal::query()
            ->where('breed', 'like', '%Bengalski%')
            ->pluck('id')
            ->all();

        // 2. Migrate existing posts
        $posts = Post::query()->withoutGlobalScopes()->get();

        foreach ($posts as $post) {
            $updated = false;

            // If sections column in DB is null, parse body
            $rawSections = $post->getRawOriginal('sections');
            if (empty($rawSections) && !empty($post->body)) {
                $parsed = Post::parseBodyToSections($post->body);
                if (!empty($parsed)) {
                    $post->sections = $parsed;
                    $updated = true;
                }
            }

            // If published_at is in the future, set to past so reader doesn't get 404
            if ($post->published_at && $post->published_at->isFuture()) {
                // Keep the date but make it September 2026 or today
                $post->published_at = now()->subDays(rand(1, 30));
                $updated = true;
            }

            if ($updated) {
                $post->saveQuietly();
            }

            // If no featured animals, link available bengal cats
            if ($post->animals()->count() === 0 && !empty($bengals)) {
                // Link 2-3 cats
                $catsToLink = array_slice($bengals, 0, 3);
                $syncData = [];
                foreach ($catsToLink as $idx => $id) {
                    $syncData[$id] = ['sort_order' => $idx];
                }
                $post->animals()->sync($syncData);
            }
        }
    }

    public function down(): void
    {
        // Non-destructive: keep sections
    }
};
