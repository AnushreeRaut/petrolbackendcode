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
        Schema::create('balance_handloans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hand_loans_id')->constrained('hand_loans')->onDelete('cascade');
            $table->string('voucher_type')->nullable();
            $table->decimal('cr_amount', 10, 2)->nullable();
            $table->decimal('dr_amount', 10, 2)->nullable();
            $table->decimal('bal_amount', 10, 2)->nullable();
            $table->text('narration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_handloans');
    }
};
