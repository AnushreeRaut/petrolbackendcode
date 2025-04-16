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
        Schema::table('hand_loans', function (Blueprint $table) {
            $table->string('amount_in_words'); // Add the new column for the amount in words
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hand_loans', function (Blueprint $table) {
            $table->dropColumn('amount_in_words'); // Drop the column if rolling back
        });
    }
};
