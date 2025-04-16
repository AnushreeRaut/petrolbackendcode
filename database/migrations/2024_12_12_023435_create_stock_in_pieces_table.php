<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockInPiecesTable extends Migration
{
    public function up()
    {
        Schema::create('stock_in_pieces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oil_product_id')->constrained('oil_products')->onDelete('cascade'); // Correctly references oil_products table
            $table->string('grade');
            $table->string('color');
            $table->decimal('mrp', 10, 2);
            $table->decimal('volume_per_pcs', 10, 2);
            $table->string('vol_type'); // e.g., liters, gallons
            $table->integer('pieces_purchase');
            $table->integer('per_tcases'); // Assuming this means pieces per case
            $table->integer('total_pcs'); // Total pieces calculated from pieces_purchase and per_tcases
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_in_pieces');
    }
}
