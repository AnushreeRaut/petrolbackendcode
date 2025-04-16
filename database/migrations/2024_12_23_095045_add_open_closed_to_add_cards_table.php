<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('add_cards', function (Blueprint $table) {
            $table->boolean('open_closed')->default(false);  // Add the open_closed field
        });
    }

    public function down(): void
    {
        Schema::table('add_cards', function (Blueprint $table) {
            $table->dropColumn('open_closed');  // Drop the open_closed field if rolling back
        });
    }
};
