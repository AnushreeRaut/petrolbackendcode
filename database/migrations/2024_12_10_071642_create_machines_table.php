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
        Schema::create('machines', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('dispensing_unit_no'); // Dispensing Unit Number
            $table->string('make'); // Make of the machine
            $table->string('serial_no'); // Serial Number
            $table->string('connected_tanks'); // Tanks connected to the machine
            // $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade');
            $table->integer('number_of_nozzles'); // Number of Nozzles
            $table->float('opening_reading', 8, 2); // Opening Reading
            $table->unsignedBigInteger('added_by'); // User who added the machine
            $table->unsignedBigInteger('updated_by'); // User who last updated the machine
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
