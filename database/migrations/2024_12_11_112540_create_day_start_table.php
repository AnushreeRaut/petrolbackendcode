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
        Schema::create('day_start', function (Blueprint $table) {
            $table->id();
            $table->decimal('ms_rate_day', 10, 2);
            $table->decimal('speed_rate_day', 10, 2);
            $table->decimal('hsd_rate_day', 10, 2);
            $table->decimal('ms_last_day', 10, 2);
            $table->decimal('speed_last_day', 10, 2);
            $table->decimal('hsd_last_day', 10, 2);
            $table->decimal('ms_diff', 10, 2);
            $table->decimal('speed_diff', 10, 2);
            $table->decimal('hsd_diff', 10, 2);
            $table->date('date')->nullable();
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_start');
    }
};
