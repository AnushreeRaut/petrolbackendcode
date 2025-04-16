<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAddCardsTable extends Migration
{
    public function up(): void
    {
        Schema::table('add_cards', function (Blueprint $table) {
            // Drop unique constraints
            $table->dropUnique(['petrol_card_machine_no']);
            $table->dropUnique(['petrol_card_no']);
        });
    }

    public function down(): void
    {
        Schema::table('add_cards', function (Blueprint $table) {
            // Re-add unique constraints if rolling back
            $table->unique('petrol_card_machine_no');
            $table->unique('petrol_card_no');
        });
    }
}
