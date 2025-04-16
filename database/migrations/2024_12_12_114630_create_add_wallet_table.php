<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddWalletTable extends Migration
{
    public function up(): void
    {
        Schema::create('add_wallet', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('add_wallet');
    }
}
