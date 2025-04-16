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
        Schema::table('nozzles', function (Blueprint $table) {
            $table->date('nozzle_stamping_date')->nullable()->after('side2');
            $table->date('nozzle_next_due_date')->nullable()->after('nozzle_stamping_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nozzles', function (Blueprint $table) {
            $table->dropColumn('nozzle_stamping_date');
            $table->dropColumn('nozzle_next_due_date');
        });
    }
};
