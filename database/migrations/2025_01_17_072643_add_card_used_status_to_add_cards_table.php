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
        Schema::table('add_cards', function (Blueprint $table) {
            $table->boolean('card_used_status')->default(false)->after('batch_no'); // Add the field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('add_cards', function (Blueprint $table) {
            $table->dropColumn('card_used_status'); // Rollback the field
        });
    }
};
