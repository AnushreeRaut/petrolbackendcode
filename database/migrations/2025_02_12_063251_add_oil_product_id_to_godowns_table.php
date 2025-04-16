<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('godowns', function (Blueprint $table) {
        $table->foreignId('oil_product_id')->constrained('oil_products')->onDelete('cascade'); // Correctly
    });
}

public function down()
{
    Schema::table('godowns', function (Blueprint $table) {
        $table->dropColumn('oil_product_id');
    });
}

};
