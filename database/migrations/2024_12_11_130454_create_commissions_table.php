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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_feeding_id')->constrained('invoice_feedings')->onDelete('cascade'); // Foreign key for invoice_feedings
            $table->decimal('total_amount', 15, 2); // Total amount
            $table->decimal('kl_liters', 15, 3); // KL Liters
            $table->decimal('purchase_per_liter', 15, 2); // Purchase price per liter
            $table->decimal('selling_price', 15, 2); // Selling price per liter
            $table->decimal('diff_comm', 15, 2); // Difference commission
            $table->unsignedBigInteger('added_by'); // Added by user ID
            $table->unsignedBigInteger('updated_by')->nullable(); // Updated by user ID
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
