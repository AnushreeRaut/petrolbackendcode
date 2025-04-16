<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('petrol_invoice_feeding', function (Blueprint $table) {
            $table->decimal('total_amt', 15, 2)->change(); // Increase precision
        });
    }

    public function down(): void
    {
        Schema::table('petrol_invoice_feeding', function (Blueprint $table) {
            $table->decimal('total_amt', 10, 2)->change(); // Revert to original
        });
    }
};
