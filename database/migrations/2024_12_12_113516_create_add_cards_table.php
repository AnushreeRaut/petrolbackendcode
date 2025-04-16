<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddCardsTable extends Migration
{
    public function up(): void
    {
        Schema::create('add_cards', function (Blueprint $table) {
            $table->id();
            $table->string('petrol_card_machine_no')->unique();
            $table->string('petrol_card_no')->unique();
            $table->string('current_batch_no')->nullable();
            $table->string('last_batch_no')->nullable();
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('add_cards');
    }
}
