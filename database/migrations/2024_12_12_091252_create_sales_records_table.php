<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesRecordsTable extends Migration
{
    public function up(): void
    {
        Schema::create('sales_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oil_product_id')->constrained('oil_products')->onDelete('cascade'); // Correctly references oil_products table
            $table->string('product_name');
            $table->float('volume', 8, 2);
            $table->float('rate', 8, 2);
            $table->float('morning_sale_1', 8, 2)->nullable();
            $table->float('morning_sale_2', 8, 2)->nullable();
            $table->float('morning_sale_3', 8, 2)->nullable();
            $table->float('morning_sale_4', 8, 2)->nullable();
            $table->float('morning_sale_5', 8, 2)->nullable();
            $table->float('morning_sale_6', 8, 2)->nullable();
            $table->float('morning_sale_total', 8, 2)->nullable();
            $table->float('evening_sale_1', 8, 2)->nullable();
            $table->float('evening_sale_2', 8, 2)->nullable();
            $table->float('evening_sale_3', 8, 2)->nullable();
            $table->float('evening_sale_4', 8, 2)->nullable();
            $table->float('evening_sale_5', 8, 2)->nullable();
            $table->float('evening_sale_6', 8, 2)->nullable();
            $table->float('evening_sale_total', 8, 2)->nullable();
            $table->float('total_1', 8, 2)->nullable();
            $table->float('total_2', 8, 2)->nullable();
            $table->float('total_3', 8, 2)->nullable();
            $table->float('total_4', 8, 2)->nullable();
            $table->float('total_5', 8, 2)->nullable();
            $table->float('total_6', 8, 2)->nullable();
            $table->float('total_sum', 8, 2)->nullable();
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_records');
    }
}
