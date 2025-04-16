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
        Schema::create('petrol_invoice_feeding', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade');
            $table->string('invoice_no'); // Invoice number
            $table->decimal('kl_qty', 10, 2); // KL/QTY
            $table->decimal('rate', 10, 2); // Rate
            $table->decimal('value', 10, 2); // Value
            $table->decimal('tax_amt', 10, 2); // Tax amount
            $table->decimal('prod_amt', 10, 2); // Product amount
            $table->decimal('vat_lst_value', 10, 2); // VAT %
            $table->decimal('vat_lst', 10, 2); // VAT/LST
            $table->decimal('cess', 10, 2); // Cess
            $table->decimal('tcs', 10, 2); // TCS
            $table->decimal('total_amt', 10, 2); // T.AMT
            $table->decimal('tds_percent', 5, 2); // TDS(%)
            $table->decimal('lfr_rate', 10, 2); // LFR RATE
            $table->decimal('cgst', 10, 2); // CGST
            $table->decimal('sgst', 10, 2); // SGST
            $table->decimal('tds_lfr', 10, 2); // TDS(LFR)
            $table->unsignedBigInteger('added_by'); // Added by user ID
            $table->unsignedBigInteger('updated_by')->nullable(); // Updated by user ID
            $table->timestamps(); // For created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petrol_invoice_feeding');
    }
};
