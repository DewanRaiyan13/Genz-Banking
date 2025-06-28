<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MoneyReceivedNotification extends Notification
{
    use Queueable;

    protected $amount;
    protected $sender;

    public function __construct($amount, $sender)
    {
        $this->amount = $amount;
        $this->sender = $sender;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "You have received $ {$this->amount} from {$this->sender->name}.",
        ];
    }
}
