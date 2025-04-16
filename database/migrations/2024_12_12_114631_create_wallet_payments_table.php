<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletPaymentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('add_wallet_id')->constrained('add_wallet')->onDelete('cascade');
            $table->integer('number_of_trans');
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_payments');
    }
}
