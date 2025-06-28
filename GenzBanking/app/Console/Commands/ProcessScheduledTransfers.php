<?php

namespace App\Console\Commands;

use App\Models\ScheduledTransfer;
use App\Models\Wallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessScheduledTransfers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfers:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending scheduled transfers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = now();

        // Fetch and process pending scheduled transfers
        $transfers = ScheduledTransfer::where('status', 'pending')
            ->where('scheduled_at', '<=', $now)
            ->get();

        if ($transfers->isEmpty()) {
            $this->info('No pending transfers to process.');
            return \Symfony\Component\Console\Command\Command::SUCCESS; // Fixed reference
        }

        foreach ($transfers as $transfer) {
            DB::transaction(function () use ($transfer) {
                $senderWallet = Wallet::where('user_id', $transfer->sender_id)->first();
                $recipientWallet = Wallet::where('user_id', $transfer->recipient_id)->first();

                // Check if sender has sufficient balance
                if ($senderWallet && $senderWallet->balance >= $transfer->amount) {
                    // Deduct amount from sender's wallet
                    $senderWallet->decrement('balance', $transfer->amount);

                    // Add amount to recipient's wallet
                    $recipientWallet->increment('balance', $transfer->amount);

                    // Update transfer status to completed
                    $transfer->update(['status' => 'completed']);

                    // Log success
                    $this->info("Processed transfer #{$transfer->id}");
                } else {
                    // Log insufficient balance
                    $transfer->update(['status' => 'failed']);
                    $this->error("Failed transfer #{$transfer->id} due to insufficient balance.");
                }
            });
        }

        return \Symfony\Component\Console\Command\Command::SUCCESS; // Fixed reference
    }
}
