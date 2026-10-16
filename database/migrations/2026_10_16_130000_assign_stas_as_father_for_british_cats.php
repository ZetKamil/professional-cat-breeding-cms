<?php

declare(strict_types=1);

use App\Models\Animal;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Znajdź istniejącego reproduktora Stasia w bazie danych
        $stas = Animal::where('breed', 'Kot Brytyjski')
            ->where(fn ($q) => $q->where('slug', 'stas')->orWhere('name', 'like', '%Staś%')->orWhere('name', 'like', '%Stas%'))
            ->first();

        // 2. Znajdź istniejącą kotkę hodowlaną Nel w bazie danych
        $nel = Animal::where('breed', 'Kot Brytyjski')
            ->where(fn ($q) => $q->where('slug', 'nel')->orWhere('name', 'Nel'))
            ->first();

        if (! $stas) {
            return;
        }

        // 3. Przypisz ojcostwo Stasia (oraz matkę Nel jeśli brak) kotom brytyjskim z miotu A
        $britishSlugs = ['ada', 'alma', 'atena', 'atos'];

        foreach ($britishSlugs as $slug) {
            $cat = Animal::where('breed', 'Kot Brytyjski')
                ->where('slug', $slug)
                ->first();

            if (! $cat) {
                continue;
            }

            $updates = [];

            if (! $cat->father_id) {
                $updates['father_id'] = $stas->id;
            }

            if (! $cat->mother_id && $nel) {
                $updates['mother_id'] = $nel->id;
            }

            if (! empty($updates)) {
                $cat->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $stas = Animal::where('breed', 'Kot Brytyjski')
            ->where(fn ($q) => $q->where('slug', 'stas')->orWhere('name', 'like', '%Staś%')->orWhere('name', 'like', '%Stas%'))
            ->first();

        if ($stas) {
            Animal::where('breed', 'Kot Brytyjski')
                ->whereIn('slug', ['ada', 'alma', 'atena', 'atos'])
                ->where('father_id', $stas->id)
                ->update(['father_id' => null]);
        }
    }
};
