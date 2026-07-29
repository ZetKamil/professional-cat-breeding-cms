<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the animals table — the core entity of the breeding domain.
 *
 * Design decisions:
 * - ULID primary key per RULES.md (§10)
 * - Self-referencing foreign keys for pedigree (mother_id, father_id)
 * - Soft deletes — business data must never disappear
 * - Indexed columns: slug, status, gender, breed, is_featured, is_published, published_at
 * - SEO fields (meta_title, meta_description) for per-page optimization
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animals', function (Blueprint $table) {
            // Primary key — ULID per architecture rules
            $table->ulid('id')->primary();

            // Identity
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('breed')->index();
            $table->string('color')->nullable();

            // Classification
            $table->string('gender')->index();          // AnimalGender enum
            $table->string('status')->index();           // AnimalStatus enum
            $table->string('type')->default('cat');      // AnimalType enum

            // Dates
            $table->date('date_of_birth')->nullable();

            // Content
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();

            // Pedigree — self-referencing for family tree
            $table->foreignUlid('mother_id')
                ->nullable()
                ->constrained('animals')
                ->nullOnDelete();

            $table->foreignUlid('father_id')
                ->nullable()
                ->constrained('animals')
                ->nullOnDelete();

            // Display control
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedInteger('sort_order')->default(0);

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
