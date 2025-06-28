<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    // Specify the table name since it's not the conventional plural
    protected $table = 'wallet';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'balance',
        'currency',
        'user_id',
    ];

    protected $casts = [
        'balance' => 'decimal:2' // Ensures balance is always treated as a decimal with 2 places
    ];

    /**
     * Relationship to the user who owns this wallet
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transactions where this wallet was the sender
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'sender_wallet_id');
    }

    /**
     * Transactions where this wallet was the receiver
     */
    public function receivedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'receiver_wallet_id');
    }

    /**
     * Check if wallet has sufficient balance for a transfer
     */
    public function canTransfer(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    /**
     * Withdraw amount from wallet
     * @throws \RuntimeException if insufficient funds
     */
    public function withdraw(float $amount): void
    {
        if (!$this->canTransfer($amount)) {
            throw new \RuntimeException('Insufficient funds in wallet');
        }

        $this->balance -= $amount;
        $this->save();
    }

    /**
     * Deposit amount to wallet
     */
    public function deposit(float $amount): void
    {
        $this->balance += $amount;
        $this->save();
    }

    /**
     * Get all transactions involving this wallet (both sent and received)
     */
    public function allTransactions()
    {
        // Fetch regular transactions
        $regularTransactions = Transaction::where('sender_wallet_id', $this->id)
            ->orWhere('receiver_wallet_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Debugging output for regular transactions
        if ($regularTransactions->isEmpty()) {
            logger('No regular transactions found for wallet ID: ' . $this->id);
        } else {
            logger('Regular Transactions:', $regularTransactions->toArray());
        }

        // Fetch multi-currency transfers
        $multiCurrencyTransfers = MultiCurrencyTransfer::where('sender_wallet_id', $this->id)
            ->orWhere('receiver_wallet_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Debugging output for multi-currency transfers
        if ($multiCurrencyTransfers->isEmpty()) {
            logger('No multi-currency transfers found for wallet ID: ' . $this->id);
        } else {
            logger('Multi-Currency Transfers:', $multiCurrencyTransfers->toArray());
        }

        // Merge both collections and sort by date
        $allTransactions = $regularTransactions->merge($multiCurrencyTransfers)->sortByDesc('created_at');

        // Debugging output for all transactions
        if ($allTransactions->isEmpty()) {
            logger('No transactions found after merging for wallet ID: ' . $this->id);
        } else {
            logger('All Transactions:', $allTransactions->toArray());
        }

        return $allTransactions;
    }

    /**
     * Get current balance formatted with currency symbol
     */
    // public function getFormattedBalanceAttribute(): string
    // {
    //     return '$' . number_format($this->balance, 2);
    // }

    /**
     * Get wallet name with balance (e.g., "Primary Wallet ($100.00)")
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->formatted_balance})";
    }
}