<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_holder_name');
            $table->string('payment_name');
            $table->string('payment_no');
            $table->date('payment_start_date');
            $table->date('payment_end_date');
            $table->string('mode');
            $table->date('date');
            $table->string('payment_account');
            $table->string('agent_name');
            $table->string('contact_no');
            $table->string('payment_model');
            $table->date('payment_date');
            $table->decimal('payment_amt', 10, 2);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}
