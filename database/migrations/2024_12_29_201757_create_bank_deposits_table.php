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
        Schema::create('bank_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('add_bank_deposit_id')->constrained('add_bank_deposits')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('account_number')->nullable(); // Bank Account Number (nullable)
            $table->string('amount_words')->nullable(); // Bank Branch
            $table->unsignedBigInteger('added_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_deposits');
    }
};
