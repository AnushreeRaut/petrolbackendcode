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
        Schema::create('nozzles', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('machine_id')->constrained('machines')->onDelete('cascade'); // Belongs to a machine
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade'); // Belongs to a tank
            $table->string('nozzle_number'); // Unique identifier for the nozzle
            $table->float('opening_reading', 8, 2)->nullable();
            $table->boolean('side1'); // Changed to boolean
            $table->boolean('side2'); // Changed to boolean
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nozzles');
    }
};
