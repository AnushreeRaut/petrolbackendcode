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
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('add_advance_client_id')->constrained('add_advance_client')->onDelete('cascade');
            $table->string('voucher_type'); // Type of voucher
            $table->decimal('amount', 15, 2); // Advance amount
            $table->text('narration')->nullable(); // Narration for the advance
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
        Schema::dropIfExists('advances');
    }
};
