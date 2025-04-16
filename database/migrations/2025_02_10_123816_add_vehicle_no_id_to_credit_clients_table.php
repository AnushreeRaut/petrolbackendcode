<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{  public function up(): void
    {
        Schema::table('credit_clients', function (Blueprint $table) {
            $table->foreignId('vehicle_no_id')
                ->nullable()
                ->constrained('vehicles')
                ->onDelete('set null'); // Adjust the referenced table as needed
        });
    }

    public function down(): void
    {
        Schema::table('credit_clients', function (Blueprint $table) {
            $table->dropForeign(['vehicle_no_id']);
            $table->dropColumn('vehicle_no_id');
        });
    }
};
