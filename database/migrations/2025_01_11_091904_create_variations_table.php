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
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fuel_sales_details_id')->constrained('fuel_sales_details')->onDelete('cascade'); // Foreign key for invoice_feedings
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade');
            $table->foreignId('petrol_invoice_feeding_id')->constrained('petrol_invoice_feeding')->onDelete('cascade');
            $table->decimal('open_stk', 10, 2);
            $table->decimal('purchase', 10, 2);
            $table->decimal('total_stk', 10, 2);
            $table->decimal('a_sale', 10, 2);
            $table->decimal('bal_stk', 10, 2);
            $table->decimal('actual_bal_stk', 10, 2);
            $table->decimal('variation', 10, 2);
            $table->decimal('t_variation', 10, 2);
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variations');
    }
};
