<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('client_credits', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('updated_by'); // Adding status as boolean with default true
        });
    }

    public function down(): void
    {
        Schema::table('client_credits', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
