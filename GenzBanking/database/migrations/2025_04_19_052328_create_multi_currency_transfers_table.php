<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('multi_currency_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_wallet_id')->constrained('wallet')->onDelete('cascade');
            $table->foreignId('receiver_wallet_id')->constrained('wallet')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('sender_currency');
            $table->string('receiver_currency');
            $table->decimal('conversion_rate', 12, 4);
            $table->decimal('fee', 12, 2)->default(0);
            $table->decimal('converted_amount', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('multi_currency_transfers');
    }
};
