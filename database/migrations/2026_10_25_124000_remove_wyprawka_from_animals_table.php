<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('animals')
            ->where('description', 'like', '%Wraz z wyprawką%')
            ->update([
                'description' => DB::raw("REPLACE(description, 'Wraz z wyprawką otrzyma', 'Otrzyma')"),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('animals')
            ->where('slug', 'carmen')
            ->update([
                'description' => DB::raw("REPLACE(description, 'Otrzyma rodowód', 'Wraz z wyprawką otrzyma rodowód')"),
            ]);
    }
};
