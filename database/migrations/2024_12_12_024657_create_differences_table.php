<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDifferencesTable extends Migration
{
    public function up()
    {
        Schema::create('differences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oil_product_id')->constrained('oil_products')->onDelete('cascade'); // Correctly references oil_products table            $table->decimal('volume_per_pcs', 10, 2);
            $table->string('vol_type'); // e.g., liters, gallons
            $table->decimal('mrp_price', 10, 2);
            $table->decimal('landing_price', 10, 2);
            $table->decimal('difference_per_pc', 10, 2);
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
        Schema::dropIfExists('differences');
    }
}
