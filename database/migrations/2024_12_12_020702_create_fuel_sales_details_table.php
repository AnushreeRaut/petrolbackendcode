<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelSalesDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('fuel_sales_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade');
            $table->string('nozzle_name');
            $table->decimal('opening', 10, 2);
            $table->decimal('closing', 10, 2);
            $table->decimal('sale', 10, 2);
            $table->decimal('testing', 10, 2);
            $table->decimal('a_sale', 10, 2);
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fuel_sales_details');
    }
}
