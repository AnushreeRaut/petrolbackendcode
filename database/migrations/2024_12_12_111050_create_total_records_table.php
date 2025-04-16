<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTotalRecordsTable extends Migration
{
    public function up(): void
    {
        Schema::create('total_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_record_id')->constrained('stock_records')->onDelete('cascade');
            $table->foreignId('bal_id')->constrained('bal_stocks')->onDelete('cascade');
            $table->foreignId('sale_record_id')->constrained('sales_records')->onDelete('cascade');
            $table->float('total_sale', 8, 2)->nullable();
            $table->float('rate', 8, 2)->nullable();
            $table->float('total_amount', 8, 2)->nullable();
            $table->float('open_stk', 8, 2)->nullable();
            $table->float('stk_recd', 8, 2)->nullable();
            $table->float('total_stk', 8, 2)->nullable();
            $table->float('bal_stk', 8, 2)->nullable();
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('total_records');
    }
}
