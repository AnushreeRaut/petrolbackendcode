<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('retail_sales_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oil_product_id')->constrained('oil_products')->onDelete('cascade'); // Correctly
            $table->foreignId('godowns_id')->nullable()->constrained('godowns')->onDelete('cascade');
            $table->decimal('opening_stk_pcs',10, 2)->nullable();
            $table->decimal('inward_to_retail',10, 2)->nullable();
            $table->decimal('total_op_stk',10, 2)->nullable();
            $table->decimal('quality_Sale',10, 2)->nullable();
            $table->decimal('bal_stk',10, 2)->nullable();
            $table->decimal('sale_amt',10, 2)->nullable();
            $table->decimal('discount_amt',10, 2)->nullable();
            $table->decimal('act_sale_amt',10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retail_sales_records');
    }
};
