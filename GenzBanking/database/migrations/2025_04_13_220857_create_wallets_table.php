<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('wallet')) { // Check if the table already exists
            Schema::create('wallet', function (Blueprint $table) {
                $table->id(); // Primary key
                $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key
                $table->string('name')->nullable(); // Wallet name
                $table->decimal('balance', 10, 2)->default(0.00); // Wallet balance
                $table->string('currency', 3); // Currency (ISO 4217 format), no default value
                $table->timestamps(); // Created at and updated at timestamps
            });
        }
    }

    public function down()
    {
        // Drop dependent tables or constraints first
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('wallet');
        Schema::enableForeignKeyConstraints();
    }
};