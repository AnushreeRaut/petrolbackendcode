<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockRecordsTable extends Migration
{
    public function up(): void
    {
        Schema::create('stock_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oil_product_id')->constrained('oil_products')->onDelete('cascade'); // Correctly references oil_products table
            $table->string('product_name');
            $table->float('volume', 8, 2);
            $table->float('rate', 8, 2);
            $table->float('openstk_1', 8, 2)->nullable();
            $table->float('openstk_2', 8, 2)->nullable();
            $table->float('openstk_3', 8, 2)->nullable();
            $table->float('openstk_4', 8, 2)->nullable();
            $table->float('openstk_5', 8, 2)->nullable();
            $table->float('openstk_6', 8, 2)->nullable();
            $table->float('openstk_total', 8, 2)->nullable();
            $table->float('stk_received_1', 8, 2)->nullable();
            $table->float('stk_received_2', 8, 2)->nullable();
            $table->float('stk_received_3', 8, 2)->nullable();
            $table->float('stk_received_4', 8, 2)->nullable();
            $table->float('stk_received_5', 8, 2)->nullable();
            $table->float('stk_received_6', 8, 2)->nullable();
            $table->float('stk_received_total', 8, 2)->nullable();
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
        Schema::dropIfExists('stock_records');
    }
}
