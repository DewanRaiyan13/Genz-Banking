<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\TransactionNotification;
use App\Notifications\MoneyReceivedNotification;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function showTransferForm()
    {
        return view('transactions.transfer');
    }

    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'recipient' => 'required',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255'
        ]);

        $senderWallet = Auth::user()->wallet;
        $recipientWallet = $this->findRecipientWallet($validated['recipient']);

        // Check if recipient wallet exists
        if (!$recipientWallet) {
            return back()->with('error', 'Recipient wallet not found');
        }

        // Check if the sender is trying to transfer to their own wallet
        if ($senderWallet->id === $recipientWallet->id) {
            return back()->with('error', "You can't transfer money to yourself");
        }

        // Check if the sender and recipient have the same currency
        if ($senderWallet->currency !== $recipientWallet->currency) {
            return back()->with('error', "Your current balance currency doesn't match the recipient's. Initiate a Multi-currency transfer to proceed.");
        }

        // Check if the sender has sufficient balance
        if (!$senderWallet->canTransfer($validated['amount'])) {
            return back()->with('error', 'Insufficient balance');
        }

        DB::transaction(function () use ($senderWallet, $recipientWallet, $validated) {
            // Create transaction
            $transaction = Transaction::create([
                'sender_wallet_id' => $senderWallet->id,
                'receiver_wallet_id' => $recipientWallet->id,
                'amount' => $validated['amount'],
                'transaction_id' => Transaction::generateTransactionId(),
                'description' => $validated['description'],
                'status' => 'completed'
            ]);

            // Adjust balances
            $senderWallet->withdraw($validated['amount']);
            $recipientWallet->deposit($validated['amount']);

            // Notify receiver
            $recipientWallet->user->notify(
                new MoneyReceivedNotification($validated['amount'], Auth::user())
            );
        });

        return redirect()->route('dashboard')->with('success', 'Transfer completed successfully!');
    }

    public function index()
    {
        $userId = Auth::id();

        // Fetch regular transactions where the user is either the sender or receiver
        $transactions = Transaction::with(['senderWallet.user', 'receiverWallet.user'])
            ->whereHas('senderWallet', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orWhereHas('receiverWallet', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        return view('transactions.index', compact('transactions'));
    }

    public function generateReceipt($id)
    {
        $transaction = Transaction::with(['senderWallet.user', 'receiverWallet.user'])->findOrFail($id);

        $data = [
            'transaction' => $transaction,
            'date' => now()->toDateTimeString(),
        ];

        $pdf = Pdf::loadView('transactions.receipt', $data);

        return $pdf->download("transaction_receipt_{$transaction->id}.pdf");
    }

    private function findRecipientWallet($identifier)
    {
        // Try by wallet ID
        $wallet = Wallet::find($identifier);
        if ($wallet) return $wallet;

        // Try by phone number
        $user = User::where('phone', $identifier)->first();
        return $user?->wallet;
    }
}
