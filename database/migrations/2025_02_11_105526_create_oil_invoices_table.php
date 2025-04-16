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
        Schema::create('oil_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->decimal('invoice_amt', 10, 2);
            $table->foreignId('oil_product_id')->constrained('oil_products')->onDelete('cascade'); // Correctly references oil_products table
            $table->integer('purchase_t_cases')->nullable();
            $table->integer('total_cases')->nullable();
            $table->decimal('total_liters', 10, 2)->nullable();
            $table->decimal('per_unit_price', 10, 2)->nullable();
            $table->decimal('taxable_value', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('bal_amt', 10, 2)->nullable();
            $table->decimal('cgst', 10, 2)->nullable();
            $table->decimal('sgst', 10, 2)->nullable();
            $table->decimal('tcs', 10, 2)->nullable();
            $table->decimal('total_amt', 10, 2)->nullable();
            $table->integer('total_pcs')->nullable();
            $table->decimal('landing_prices', 10, 2)->nullable();
            $table->decimal('purchase_amount', 10, 2)->nullable();
            $table->decimal('other_discounts', 10, 2)->nullable();
            $table->decimal('invoice_amount', 10, 2)->nullable();
            $table->decimal('diff_per_pc', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oil_invoices');
    }
};
