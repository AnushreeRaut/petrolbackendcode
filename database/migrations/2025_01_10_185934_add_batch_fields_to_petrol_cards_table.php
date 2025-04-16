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
        Schema::table('petrol_cards', function (Blueprint $table) {
            $table->string('current_batch_no')->nullable()->after('updated_by');
            $table->string('last_batch_no')->nullable()->after('current_batch_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petrol_cards', function (Blueprint $table) {
            $table->dropColumn(['current_batch_no', 'last_batch_no']);
        });
    }
};
