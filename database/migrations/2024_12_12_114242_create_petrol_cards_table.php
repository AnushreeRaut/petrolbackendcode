<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetrolCardsTable extends Migration
{
    public function up(): void
    {
        Schema::create('petrol_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('add_cards')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petrol_cards');
    }
}
