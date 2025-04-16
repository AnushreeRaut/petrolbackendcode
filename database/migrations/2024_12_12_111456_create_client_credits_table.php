<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientCreditsTable extends Migration
{
    public function up(): void
    {
        Schema::create('client_credits', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('mobile_number', 15);
            $table->text('address')->nullable();
            $table->string('firm_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number', 20)->nullable();
            $table->string('ifsc_code', 15)->nullable();
            $table->string('branch_name')->nullable();
            $table->string('account_type')->nullable(); // e.g., Savings, Current
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_credits');
    }
}
