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
        Schema::create('bill_book_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_client_id')->constrained('credit_clients')->onDelete('cascade');
            $table->date('date')->nullable();
            $table->string('mode', 50)->nullable(); // Define length if needed
            $table->string('account', 100)->nullable(); // Increased length for flexibility
            $table->decimal('amount', 10, 2)->nullable(); // Changed to decimal
            $table->text('remark')->nullable(); // Changed to text for longer remarks
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_book_details');
    }
};
