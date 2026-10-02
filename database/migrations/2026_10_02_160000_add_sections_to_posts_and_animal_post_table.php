<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Two changes in one migration to minimise deployment steps:
 *
 * 1. posts.sections — JSON column that stores the structured article
 *    as an array of section objects (heading, body, image_url).
 *    The old `body` column is kept so existing posts remain readable.
 *
 * 2. animal_post pivot — allows editors to pin specific cattery animals
 *    to a post so they appear in the "Featured Cats" strip at the bottom
 *    of the article page.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Structured sections JSON column on posts
        Schema::table('posts', function (Blueprint $table) {
            $table->json('sections')->nullable()->after('body');
        });

        // 2. Many-to-many: posts ↔ animals
        Schema::create('animal_post', function (Blueprint $table) {
            $table->id();

            // animals uses ULIDs (string primary key)
            $table->string('animal_id');
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['animal_id', 'post_id']);
            $table->index('post_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animal_post');

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('sections');
        });
    }
};
