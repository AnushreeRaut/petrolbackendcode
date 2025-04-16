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
        Schema::create('invoice_feedings', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no'); // Invoice number
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade'); // Foreign key for tanks
            $table->decimal('kl_qty', 15, 3); // Quantity in KL
            $table->decimal('rate_per_unit', 15, 2); // Rate per unit
            $table->decimal('value', 15, 2); // Value
            $table->decimal('taxable_amount', 15, 2); // Taxable amount
            $table->decimal('product_amount', 15, 2); // Product amount
            $table->decimal('vat_percent', 5, 2); // VAT percentage
            $table->decimal('vat_lst', 15, 2); // VAT amount
            $table->decimal('cess', 15, 2); // CESS
            $table->decimal('tcs', 15, 2); // TCS
            $table->decimal('t_amount', 15, 2); // Total amount
            $table->decimal('t_invoice_amount', 15, 2); // Total invoice amount
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
        Schema::dropIfExists('invoice_feedings');
    }
};
