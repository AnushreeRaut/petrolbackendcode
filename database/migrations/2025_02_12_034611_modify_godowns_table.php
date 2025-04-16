<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('godowns', function (Blueprint $table) {
            $table->dropForeign(['stock_in_pieces_id']); // Drop foreign key first
            $table->dropColumn([
                'stock_in_pieces_id',
                'opening_stk_pcs',
                'invoice_stk_pcs',
                'outward_to_retail',
                'balance_stock',
                'balance_stock_amt',
                'added_by',
                'updated_by',
            ]);
        });
    }

    public function down()
    {
        Schema::table('godowns', function (Blueprint $table) {
            $table->foreignId('stock_in_pieces_id')->constrained('stock_in_pieces')->onDelete('cascade');
            $table->integer('opening_stk_pcs');
            $table->integer('invoice_stk_pcs');
            $table->integer('outward_to_retail');
            $table->integer('balance_stock');
            $table->decimal('balance_stock_amt', 10, 2);
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }
};
