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
        Schema::create('decantinations', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->decimal('tank_1_ms', 10, 2); // Tank 1 MS
            $table->decimal('tank_2_speed', 10, 2); // Tank 2 Speed
            $table->decimal('tank_3_hsd', 10, 2); // Tank 3 HSD
            $table->decimal('total_kl', 10, 2); // Total KL
            $table->unsignedBigInteger('added_by'); // Added by user ID
            $table->unsignedBigInteger('updated_by')->nullable(); // Updated by user ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decantinations');
    }
};
