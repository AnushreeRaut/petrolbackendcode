<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSummariesTable extends Migration
{
    public function up()
    {
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oil_product_id')->constrained('oil_products')->onDelete('cascade'); // Correctly references oil_products table
            $table->decimal('stock_in_liters', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('balance', 10, 2);
            $table->decimal('cgst', 10, 2);
            $table->decimal('sgst', 10, 2);
            $table->decimal('tcs', 10, 2);
            $table->decimal('total_amt', 10, 2);
            $table->integer('total_pcs');
            $table->decimal('landing_price', 10, 2);
            $table->decimal('purchase_amt', 10, 2);
            $table->decimal('other_discount', 10, 2)->nullable();
            $table->decimal('invoice_amt', 10, 2);
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Foreign key constraint
            // Uncomment if you have a corresponding oil_products table
            // $table->foreign('oil_product_id')->references('id')->on('oil_products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('summaries');
    }
}
