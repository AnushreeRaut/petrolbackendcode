<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGodownsTable extends Migration
{
    public function up()
    {
        Schema::create('godowns', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('oil_invoices_id')->constrained('oil_invoices')->onDelete('cascade'); // Correctly references oil_products table
            // $table->foreignId('oil_product_id')->constrained('oil_products')->onDelete('cascade'); // Correctly references oil_products table
            // $table->decimal('t_opening_stk', 10, 2)->nullable();
            // $table->decimal('outward_retail', 10, 2)->nullable();
            // $table->decimal('bal_stk', 10, 2)->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('godowns');
    }
}
