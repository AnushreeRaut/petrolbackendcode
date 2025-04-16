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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_feeding_id')->constrained('invoice_feedings')->onDelete('cascade'); // Foreign key for invoice_feedings
            $table->date('invoice_date'); // Invoice date
            $table->json('products'); // Products (stored as JSON)
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
        Schema::dropIfExists('product_details');
    }
};
