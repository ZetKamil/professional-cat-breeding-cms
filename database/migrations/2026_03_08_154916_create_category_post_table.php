<?php

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Dit is de pivot table voor de many-to-many relatie:
     * - één post kan meerdere categories hebben
     * - één category kan aan meerdere posts hangen
     *
     * We gebruiken cascadeOnDelete() hier wel bewust:
     * - als een post definitief weg is, mogen de pivot-records mee weg
     * - als een category definitief weg is, mogen de pivot-records mee weg
     */
    public function up(): void
    {
        Schema::create('category_post', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Post::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Category::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            // Voorkomt dubbele koppelingen tussen dezelfde post en category
            $table->unique(['post_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_post');
    }
};
