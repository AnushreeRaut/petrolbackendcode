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
        Schema::create('oil_invoices_details', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->decimal('invoice_amt', 10, 2);
            $table->decimal('purchase_amount', 10, 2)->nullable();
            $table->decimal('other_discounts', 10, 2)->nullable();
            $table->decimal('invoice_amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oil_invoices_details');
    }
};
