<?php

use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $posts = Post::all();

        foreach ($posts as $post) {
            $excerpt = $post->excerpt;

            // Only fix posts where excerpt has raw markdown heading or is empty
            if (empty($excerpt) || str_starts_with(trim($excerpt), '#')) {
                $cleanExcerpt = null;

                if (!empty($post->meta_description) && !str_starts_with(trim($post->meta_description), '#')) {
                    $cleanExcerpt = trim($post->meta_description);
                } else {
                    $body = (string) ($post->body ?? '');
                    // Strip markdown headings and formatting
                    $cleanText = preg_replace('/^#+.*$/m', '', $body);
                    $cleanText = preg_replace('/!\[.*?\]\(.*?\)/s', '', $cleanText);
                    $cleanText = preg_replace('/\[(.*?)\]\(.*?\)/s', '$1', $cleanText);
                    $cleanExcerpt = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($cleanText))), 160);
                }

                if (!empty($cleanExcerpt)) {
                    $post->update(['excerpt' => $cleanExcerpt]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: Cleaning excerpts does not require reversal
    }
};
