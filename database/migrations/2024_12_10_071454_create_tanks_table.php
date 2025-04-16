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
        Schema::create('tanks', function (Blueprint $table) {
            $table->id(); // Primary key (id)
            $table->string('tank_number'); // Tank Number
            $table->string('product'); // Product stored in the tank
            $table->integer('no_of_nozzles'); // Number of nozzles
            $table->float('capacity', 8, 2); // Capacity of the tank
            $table->float('opening_reading', 8, 2); // Opening reading
            $table->unsignedBigInteger('added_by'); // User who added the record
            $table->unsignedBigInteger('updated_by'); // User who updated the record
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanks');
    }
};
