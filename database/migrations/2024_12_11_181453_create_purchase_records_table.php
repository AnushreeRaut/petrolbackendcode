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
        Schema::create('purchase_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_detail_id')->constrained('product_details')->onDelete('cascade'); // Foreign key for product_details
            $table->decimal('value_1_ms', 10, 2); // Value 1 MS
            $table->decimal('value_2_speed', 10, 2); // Value 2 Speed
            $table->decimal('value_3_hsd', 10, 2); // Value 3 HSD
            $table->decimal('total_kl', 10, 2); // Total KL
            $table->unsignedBigInteger('added_by'); // Added by user ID
            $table->unsignedBigInteger('updated_by')->nullable(); // Updated by user ID
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_records');
    }
};
