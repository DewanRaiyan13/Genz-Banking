<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $transaction;
    public $type; // 'sender' or 'receiver'

    public function __construct(Transaction $transaction, $type)
    {
        $this->transaction = $transaction;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->type === 'sender' 
            ? 'Funds Transfer Confirmation' 
            : 'Funds Received Notification';

        return (new MailMessage)
            ->subject($subject)
            ->line(($this->type === 'sender' ? 'You sent ' : 'You received ') 
                . '$' . number_format($this->transaction->amount, 2))
            ->line('Transaction ID: ' . $this->transaction->transaction_id)
            ->action('View Transaction', url('/transactions/' . $this->transaction->id));
    }

    public function toArray($notifiable)
    {
        return [
            'transaction_id' => $this->transaction->transaction_id,
            'amount' => $this->transaction->amount,
            'type' => $this->type,
            'message' => $this->type === 'sender'
                ? 'You sent $' . $this->transaction->amount . ' to ' . $this->transaction->receiverWallet->user->name
                : 'You received $' . $this->transaction->amount . ' from ' . $this->transaction->senderWallet->user->name
        ];
    }
}