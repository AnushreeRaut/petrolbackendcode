<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('day_ends', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->decimal('a_ms_amt', 12, 2)->nullable();
            $table->decimal('b_speed_amt', 12, 2)->nullable();
            $table->decimal('b_hsd_amt', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->decimal('total_incoming', 12, 2)->nullable();
            $table->decimal('total_outgoing', 12, 2)->nullable();
            $table->decimal('total_balance_cash', 12, 2)->nullable();
            $table->decimal('to_bank_on_date_cash', 12, 2)->nullable();
            $table->foreignId('add_bank_deposit_id')->constrained('add_bank_deposits')->onDelete('cascade');
            $table->decimal('short', 12, 2)->nullable();
            $table->decimal('t_short', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_ends');
    }
};
