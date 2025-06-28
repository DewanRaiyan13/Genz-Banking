<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('card_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User requesting the card
            $table->foreignId('wallet_id')->constrained('wallet')->onDelete('cascade'); // Wallet ID
            $table->string('name_on_card'); // Name on the card
            $table->string('card_type'); // Card type (e.g., fetched from database)
            $table->decimal('spending_limit', 10, 2); // Spending limit
            $table->string('tin_number'); // TIN number
            $table->string('status')->default('pending'); // Status of the request
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('card_requests');
    }
};
