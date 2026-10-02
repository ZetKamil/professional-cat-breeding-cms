<?php

use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $oldNumbers = [
            '+48 789 790 846',
            '789 790 846',
            '+48789790846',
            '789790846',
        ];

        $correctNumber = '+48 514 153 204';

        // 1. Update posts table directly in DB
        $posts = DB::table('posts')->get(['id', 'body', 'sections', 'excerpt']);

        foreach ($posts as $post) {
            $updated = false;
            $newBody = $post->body;
            $newExcerpt = $post->excerpt;
            $newSections = $post->sections;

            foreach ($oldNumbers as $old) {
                if ($newBody && str_contains($newBody, $old)) {
                    $newBody = str_replace($old, $correctNumber, $newBody);
                    $updated = true;
                }
                if ($newExcerpt && str_contains($newExcerpt, $old)) {
                    $newExcerpt = str_replace($old, $correctNumber, $newExcerpt);
                    $updated = true;
                }
                if ($newSections && str_contains($newSections, $old)) {
                    $newSections = str_replace($old, $correctNumber, $newSections);
                    $updated = true;
                }
            }

            if ($updated) {
                DB::table('posts')->where('id', $post->id)->update([
                    'body'       => $newBody,
                    'excerpt'    => $newExcerpt,
                    'sections'   => $newSections,
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Don't revert to wrong number
    }
};
