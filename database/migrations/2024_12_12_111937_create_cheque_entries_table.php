<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChequeEntriesTable extends Migration
{
    public function up(): void
    {
        Schema::create('cheque_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('add_client_credit_id')->constrained('client_credits')->onDelete('cascade');
            $table->string('bill_no');
            $table->date('bill_date');
            $table->decimal('bill_amt', 10, 2);
            $table->string('cheque_no')->unique();
            $table->decimal('amount', 10, 2);
            $table->date('cheque_date');
            $table->string('bank_name');
            $table->enum('status', ['pending', 'cleared', 'bounced'])->default('pending');
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cheque_entries');
    }
}
