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
        Schema::create('add_petrol_invoice', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade');
            $table->decimal('rate_per_unit', 10, 2); // Rate per unit
            $table->decimal('tax_amt_per_amt', 10, 2); // Tax amount per amount
            $table->decimal('vat_lst', 5, 2); // VAT list percentage
            $table->decimal('cess_per_unit', 10, 2); // Cess per unit
            $table->decimal('tcs_per_unit', 10, 2); // TCS per unit
            $table->decimal('194Q_tds', 10, 2); // TDS under section 194Q
            $table->decimal('LFR_prt_kl', 10, 2); // LFR price per KL
            $table->decimal('Cgst', 5, 2); // CGST percentage
            $table->decimal('SGST', 5, 2); // SGST percentage
            $table->decimal('194I_tds_lfr', 10, 2); // TDS under section 194I for LFR
            $table->boolean('is_active')->default(true); // Active status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_petrol_invoice');
    }
};
