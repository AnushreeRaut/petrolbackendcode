<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockInLitersTable extends Migration
{
    public function up()
    {
        Schema::create('stock_in_liters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oil_product_id')->constrained('oil_products')->onDelete('cascade'); // Correctly references oil_products table
            $table->decimal('vol_per_pcs', 10, 2); // Volume per piece
            $table->string('vol_type'); // e.g., liters, gallons
            $table->decimal('total_liters', 10, 2);
            $table->decimal('perunit_price', 10, 2);
            $table->decimal('taxable_value', 10, 2);
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
        Schema::dropIfExists('stock_in_liters');
    }
}
