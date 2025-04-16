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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Role name
            $table->text('description')->nullable(); // Role description
            $table->boolean('is_active')->default(true); // Active status
            $table->unsignedBigInteger('added_by')->nullable(); // Added by user
            $table->unsignedBigInteger('updated_by')->nullable(); // Updated by user
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
