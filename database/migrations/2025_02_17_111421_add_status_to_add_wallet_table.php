<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('add_wallet', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('updated_by');
        });
    }

    public function down(): void
    {
        Schema::table('add_wallet', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
