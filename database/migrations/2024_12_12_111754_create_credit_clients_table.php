<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditClientsTable extends Migration
{
    public function up(): void
    {
        Schema::create('credit_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('add_client_credit_id')->constrained('client_credits')->onDelete('cascade');
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade');
            $table->foreignId('vehicle_no_id')
            ->nullable()
            ->constrained('vehicles')
            ->onDelete('set null'); // Adjust the referenced table as needed
            $table->string('bill_no');
            $table->decimal('amount', 10, 2);
            $table->decimal('rate', 10, 2);
            $table->decimal('quantity_in_liter', 10, 2);
            $table->string('amt_wrds'); // For amount in words
            $table->string('vehicle_no')->nullable();
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_clients');
    }
}
