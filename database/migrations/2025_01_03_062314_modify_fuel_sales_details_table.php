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
        Schema::table('fuel_sales_details', function (Blueprint $table) {
            // Drop the existing foreign key and column
            $table->dropForeign(['invoice_feeding_id']);
            $table->dropColumn('invoice_feeding_id');

            // Add the new foreign key column
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuel_sales_details', function (Blueprint $table) {
            // Rollback the changes by removing the new column and adding the old one back
            $table->dropForeign(['tank_id']);
            $table->dropColumn('tank_id');

            $table->foreignId('invoice_feeding_id')->constrained('invoice_feedings')->onDelete('cascade');
        });
    }
};
