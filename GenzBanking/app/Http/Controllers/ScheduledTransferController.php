<?php

namespace App\Http\Controllers;

use App\Models\ScheduledTransfer;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduledTransferController extends Controller
{
    // Show the form to schedule a transfer
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get(); // Exclude the logged-in user
        return view('scheduled_transfers.create', compact('users'));
    }

    // Store a new scheduled transfer
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_identifier' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'scheduled_at' => 'required|date|after:now',
            'description' => 'nullable|string|max:255',
        ]);

        $senderWallet = Auth::user()->wallet;
        $recipientWallet = $this->findRecipientWallet($validated['recipient_identifier']);

        // Check if the recipient has a wallet
        if (!$recipientWallet) {
            return back()->with('error', 'Recipient wallet not found.');
        }

        // Check if the sender and recipient have the same currency
        if ($senderWallet->currency !== $recipientWallet->currency) {
            return back()->with('error', 'Sender and recipient must have the same currency to schedule a transfer.');
        }

        ScheduledTransfer::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $recipientWallet->user_id,
            'amount' => $validated['amount'],
            'scheduled_at' => $validated['scheduled_at'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return redirect()->route('scheduled_transfers.index')->with('success', 'Transfer scheduled successfully.');
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

    // List all scheduled transfers for the logged-in user
    public function index()
    {
        $scheduledTransfers = ScheduledTransfer::where('sender_id', Auth::id())->get();
        return view('scheduled_transfers.index', compact('scheduledTransfers'));
    }

    // Show the form to edit a scheduled transfer
    public function edit(ScheduledTransfer $scheduledTransfer)
    {
        $this->authorize('update', $scheduledTransfer);

        $users = User::where('id', '!=', Auth::id())->get();
        return view('scheduled_transfers.edit', compact('scheduledTransfer', 'users'));
    }

    // Update a scheduled transfer
    public function update(Request $request, ScheduledTransfer $scheduledTransfer)
    {
        $this->authorize('update', $scheduledTransfer);

        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'scheduled_at' => 'required|date|after:now',
            'description' => 'nullable|string|max:255',
        ]);

        $senderWallet = Auth::user()->wallet;
        $recipientWallet = Wallet::where('user_id', $validated['recipient_id'])->first();

        // Check if the recipient has a wallet
        if (!$recipientWallet) {
            return back()->with('error', 'Recipient does not have a wallet.');
        }

        // Check if the sender and recipient have the same currency
        if ($senderWallet->currency !== $recipientWallet->currency) {
            return back()->with('error', 'Sender and recipient must have the same currency to schedule a transfer.');
        }

        $scheduledTransfer->update($validated);

        return redirect()->route('scheduled_transfers.index')->with('success', 'Scheduled transfer updated successfully.');
    }

    // Cancel a scheduled transfer
    public function destroy(ScheduledTransfer $scheduledTransfer)
    {
        $this->authorize('delete', $scheduledTransfer);

        $scheduledTransfer->update(['status' => 'canceled']);

        return redirect()->route('scheduled_transfers.index')->with('success', 'Scheduled transfer canceled successfully.');
    }
}
