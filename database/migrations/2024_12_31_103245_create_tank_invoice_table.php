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
        Schema::create('tank_invoice', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade'); // Foreign key for tank
            $table->foreignId('add_petrol_invoice_id')->constrained('add_petrol_invoice')->onDelete('cascade'); // Foreign key for invoice
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tank_invoice');
    }
};
