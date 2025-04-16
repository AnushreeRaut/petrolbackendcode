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
        Schema::create('machine_wise_groupings', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('machine_id')->constrained('machines')->onDelete('cascade');
            $table->string('nozzle_number'); // Selected nozzle number
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade');
            $table->unsignedBigInteger('added_by'); // User who added the record
            $table->unsignedBigInteger('updated_by'); // User who last updated the record
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_wise_groupings');
    }
};
