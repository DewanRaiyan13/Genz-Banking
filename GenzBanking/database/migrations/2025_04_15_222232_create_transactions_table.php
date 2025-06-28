<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('transactions')) { // Check if the table already exists
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sender_wallet_id')->constrained('wallet')->onDelete('cascade');
                $table->foreignId('receiver_wallet_id')->constrained('wallet')->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->string('transaction_id')->unique();
                $table->string('status')->default('completed');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('transactions');
        Schema::enableForeignKeyConstraints();
    }
};