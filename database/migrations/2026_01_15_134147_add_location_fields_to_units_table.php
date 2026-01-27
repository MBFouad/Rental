<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->after('location')->constrained()->nullOnDelete();
            $table->foreignId('area_id')->nullable()->after('city_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['area_id', 'city_id']);
        });
    }
};
