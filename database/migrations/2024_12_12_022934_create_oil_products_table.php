<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOilProductsTable extends Migration
{
    public function up()
    {
        Schema::create('oil_products', function (Blueprint $table) {
            $table->id();
            $table->decimal('op_stock', 10, 2);
            $table->string('product_name');
            $table->string('grade');
            $table->string('color');
            $table->decimal('mrp', 10, 2);
            $table->decimal('volume', 10, 2);
            $table->string('price_per_piece');
            $table->integer('pieces_per_case');
            $table->string('type');
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('oil_products');
    }
}
