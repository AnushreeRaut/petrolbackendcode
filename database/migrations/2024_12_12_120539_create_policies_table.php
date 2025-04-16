<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliciesTable extends Migration
{
    public function up(): void
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('policy_holder_name');
            $table->string('policy_name');
            $table->string('policy_no');
            $table->date('policy_start_date');
            $table->date('policy_end_date');
            $table->string('paying_term');
            $table->date('date');
            $table->decimal('policy_amt', 10, 2);
            $table->string('agent_name');
            $table->string('contact_number');
            $table->string('payment_mode');
            $table->date('payment_date');
            $table->decimal('payment_amt', 10, 2);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
}
