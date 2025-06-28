<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedWalletUserTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shared_wallet_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shared_wallet_id'); // Foreign key to shared_wallets table
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('shared_wallet_id')
                ->references('id')
                ->on('shared_wallets')
                ->onDelete('cascade');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shared_wallet_user');
    }
}
