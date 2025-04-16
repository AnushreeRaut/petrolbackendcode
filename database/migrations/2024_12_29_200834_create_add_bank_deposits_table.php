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
        Schema::create('add_bank_deposits', function (Blueprint $table) {
        $table->id();
        $table->string('bank_name');
        $table->string('account_number')->nullable();
        $table->string('account_name')->nullable();
        $table->string('bank_branch')->nullable();
        $table->enum('account_type', ['Saving Account', 'Current Account']);
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
        Schema::dropIfExists('add_bank_deposits');
    }
};
