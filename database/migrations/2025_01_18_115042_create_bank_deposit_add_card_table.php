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
        Schema::create('bank_deposit_add_card', function (Blueprint $table) {
            $table->id();
            $table->foreignId('add_bank_deposit_id')->constrained('add_bank_deposits')->onDelete('cascade');
            $table->foreignId('add_card_id')->constrained('add_cards')->onDelete('cascade');
            $table->integer('tid_no');
            $table->string('narration');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_deposit_add_card');
    }
};
