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
        Schema::create('tank_fule_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade');
            $table->decimal('rate', 10, 2);
            $table->decimal('total_sales', 10, 2);
            $table->decimal('total_a_sales', 10, 2);
            $table->decimal('total_testing', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tank_fule_sales');
    }
};
