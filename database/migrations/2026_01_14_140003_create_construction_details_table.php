<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('construction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_price', 15, 2);
            $table->decimal('down_payment_amount', 15, 2)->nullable();
            $table->decimal('down_payment_percentage', 5, 2)->nullable();
            $table->date('expected_completion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('construction_details');
    }
};
