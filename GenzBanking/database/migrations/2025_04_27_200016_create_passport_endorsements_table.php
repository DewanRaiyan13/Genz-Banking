<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('passport_endorsements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User requesting endorsement
            $table->foreignId('wallet_id')->constrained('wallet')->onDelete('cascade'); // Wallet ID
            $table->string('passport_number'); // Passport number
            $table->decimal('amount_usd', 10, 2); // Amount requested in USD
            $table->string('status')->default('pending'); // Status of the request
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passport_endorsements');
    }
};
