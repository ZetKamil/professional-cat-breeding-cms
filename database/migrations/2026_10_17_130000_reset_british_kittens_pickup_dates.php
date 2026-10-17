<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Reset earliest_pickup_date for British kittens back to null,
        // so each cat can be configured individually via CMS without shared bulk values.
        DB::table('animals')
            ->whereIn('slug', ['ada', 'alma', 'atena'])
            ->update([
                'earliest_pickup_date' => null,
            ]);
    }

    public function down(): void
    {
        // No-op rollback
    }
};
