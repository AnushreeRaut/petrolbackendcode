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
        Schema::create('employee_incentives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('incentive_month')->nullable();
            $table->integer('month_days')->nullable();
            $table->integer('holidays')->nullable();
            $table->integer('work_days')->nullable();
            $table->decimal('t_sale', 10, 2)->nullable();
            $table->decimal('avg_sale', 10, 2)->nullable();
            $table->decimal('amt', 10, 2)->nullable();
            $table->decimal('incentive', 10, 2)->nullable();
            $table->string('payment')->nullable();
            $table->string('bank_cheque')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_incentives');
    }
};
