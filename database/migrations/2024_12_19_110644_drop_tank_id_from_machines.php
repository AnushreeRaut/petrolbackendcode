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
        // Dropping foreign key constraint before dropping the column
        Schema::table('machines', function (Blueprint $table) {
            $table->dropForeign(['tank_id']); // Drop the foreign key constraint
            $table->dropColumn('tank_id');    // Drop the tank_id column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: re-add the tank_id column and the foreign key constraint
        Schema::table('machines', function (Blueprint $table) {
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade'); // Add the foreign key back
        });
    }
};
