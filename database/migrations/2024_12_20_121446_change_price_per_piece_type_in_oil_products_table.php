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
        Schema::table('oil_products', function (Blueprint $table) {
            $table->string('price_per_piece')->change();  // Change the column to string
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oil_products', function (Blueprint $table) {
            $table->decimal('price_per_piece', 10, 2)->change();  // Revert back to decimal
        });
    }
};
