<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndexInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('index_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_feeding_id')->constrained('invoice_feedings')->onDelete('cascade'); // Foreign key for invoice_feedings
            $table->string('product_name');
            $table->decimal('rate_per_unit', 10, 2);
            $table->decimal('taxable_amount', 10, 2);
            $table->decimal('vat_lst', 10, 2);
            $table->decimal('cess', 10, 2);
            $table->decimal('tcs', 10, 2);
            $table->decimal('tds', 10, 2);
            $table->decimal('cgst', 10, 2);
            $table->decimal('sgst', 10, 2);
            $table->decimal('lfr', 10, 2);
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Foreign key constraint
            // Uncomment if you have a corresponding invoice_feeding table
            // $table->foreign('invoice_feeding_id')->references('id')->on('invoice_feedings')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('index_invoices');
    }
}
