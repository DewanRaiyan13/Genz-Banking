<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedWalletMembersTable extends Migration
{
    public function up(): void
    {
        Schema::create('shared_wallet_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shared_wallet_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->unique(['shared_wallet_id', 'user_id']);

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

    public function down(): void
    {
        Schema::dropIfExists('shared_wallet_members');
    }
}
