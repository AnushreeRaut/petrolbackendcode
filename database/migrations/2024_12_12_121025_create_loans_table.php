<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_holder_name');
            $table->string('loan_name');
            $table->string('loan_no');
            $table->date('loan_start_date');
            $table->date('loan_end_date');
            $table->string('mode');
            $table->date('date');
            $table->decimal('loan_amt', 10, 2);
            $table->string('agent_name');
            $table->string('contact_number');
            $table->string('payment_model');
            $table->date('payment_date');
            $table->decimal('payment_amt', 10, 2);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
}
