<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be filled using create() or mass assignment
     */
    protected $fillable = [
        'sender_wallet_id',  // ID of the wallet sending money
        'receiver_wallet_id', // ID of the wallet receiving money
        'amount',            // Transaction amount
        'transaction_id',    // Unique transaction ID
        'status',            // Transaction status (completed, failed, etc.)
        'description',       // Optional description
        'scheduled_for',     // For future scheduled transactions
        'executed_at'        // When transaction was actually processed
    ];

    /**
     * The attributes that should be cast to native types.
     * Ensures amount is always treated as a decimal
     */
    protected $casts = [
        'amount' => 'decimal:2',  // Always store with 2 decimal places
        'scheduled_for' => 'datetime',
        'executed_at' => 'datetime'
    ];

    /**
     * Relationship to the sender's wallet
     */
    public function senderWallet()
    {
        return $this->belongsTo(Wallet::class, 'sender_wallet_id');
    }

    /**
     * Relationship to the receiver's wallet
     */
    public function receiverWallet()
    {
        return $this->belongsTo(Wallet::class, 'receiver_wallet_id');
    }

    /**
     * Generate a unique transaction ID
     */
    public static function generateTransactionId()
    {
        return 'TXN' . now()->timestamp . strtoupper(Str::random(6));
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for transactions involving a specific wallet
     */
    public function scopeForWallet($query, $walletId)
    {
        return $query->where(function($q) use ($walletId) {
            $q->where('sender_wallet_id', $walletId)
              ->orWhere('receiver_wallet_id', $walletId);
        });
    }
}