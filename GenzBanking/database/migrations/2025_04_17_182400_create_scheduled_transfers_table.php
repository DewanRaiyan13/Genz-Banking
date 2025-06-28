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
    public function up()
    {
        Schema::create('scheduled_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id'); // User scheduling the transfer
            $table->unsignedBigInteger('recipient_id'); // Recipient user
            $table->decimal('amount', 15, 2); // Transfer amount
            $table->string('status')->default('pending'); // Status: pending, completed, canceled
            $table->timestamp('scheduled_at'); // Scheduled execution time
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_transfers');
    }
};
