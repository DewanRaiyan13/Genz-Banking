<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LoanRequestNotification extends Notification
{
    use Queueable;

    protected $loanRequest;
    protected $action;

    public function __construct($loanRequest, $action = 'requested')
    {
        $this->loanRequest = $loanRequest;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $message = match ($this->action) {
            'requested' => "You have a loan request from {$this->loanRequest->borrower->name}.",
            'approved' => "Your loan request has been approved by {$this->loanRequest->lender->name}.",
            'declined' => "Your loan request has been declined by {$this->loanRequest->lender->name}.",
            default => "Loan request update.",
        };

        return [
            'message' => $message,
            'loan_request_id' => $this->loanRequest->id,
            'borrower' => $this->loanRequest->borrower->name,
            'lender' => $this->loanRequest->lender->name ?? 'N/A',
            'amount' => $this->loanRequest->amount,
            'repayment_date' => $this->loanRequest->repayment_date,
            'status' => $this->loanRequest->status,
        ];
    }
}
