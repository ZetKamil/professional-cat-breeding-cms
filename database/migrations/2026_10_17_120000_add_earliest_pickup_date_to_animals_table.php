<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->date('earliest_pickup_date')->nullable()->after('date_of_birth');
        });

        // Set earliest pickup date for British kittens (ready in 4 weeks from 2026-10-17 -> 2026-11-14)
        DB::table('animals')
            ->whereIn('slug', ['ada', 'alma', 'atena'])
            ->update([
                'earliest_pickup_date' => '2026-11-14',
            ]);
    }

    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn('earliest_pickup_date');
        });
    }
};
