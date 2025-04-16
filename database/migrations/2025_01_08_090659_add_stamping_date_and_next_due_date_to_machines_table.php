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
        Schema::table('machines', function (Blueprint $table) {
            $table->date('stamping_date')->nullable()->after('updated_by'); // Add stamping_date column
            $table->date('next_due_date')->nullable()->after('stamping_date'); // Add next_due_date column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumn(['stamping_date', 'next_due_date']); // Drop the columns if rolled back
        });
    }
};
