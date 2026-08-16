<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->string('mediable_type')->nullable()->change();
            $table->unsignedBigInteger('mediable_id')->nullable()->change();

            $table->string('caption', 2000)->nullable()->after('alt_text');
            $table->string('copyright')->nullable()->after('caption');
            $table->unsignedInteger('sort_order')->default(0)->after('copyright');
            $table->boolean('is_featured')->default(false)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['caption', 'copyright', 'sort_order', 'is_featured']);
        });
    }
};
