<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * We maken hier de hoofdtafel voor blogposts.
     * Belangrijke keuzes:
     * - user_id is nullable + nullOnDelete():
     *   als een user ooit definitief verwijderd wordt,
     *   willen we de posts niet automatisch mee verwijderen.
     * - slug is unique:
     *   nodig voor nette route model binding via slug.
     * - is_published + published_at:
     *   zo kunnen we drafts en gepubliceerde posts beheren.
     * - softDeletes():
     *   zodat restore en force delete mogelijk zijn.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body');

            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexen voor filters en sortering in de backend
            $table->index('title');
            $table->index('is_published');
            $table->index('published_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
