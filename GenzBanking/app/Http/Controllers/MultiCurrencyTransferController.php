<?php
namespace App\Http\Controllers;

use App\Models\MultiCurrencyTransfer;
use App\Models\Wallet;
use Illuminate\Http\Request;

class MultiCurrencyTransferController extends Controller
{
    public function index()
    {
        $transfers = MultiCurrencyTransfer::all();
        return view('multi_currency.transfers.index', compact('transfers'));
    }

    public function show($id)
    {
        $transfer = MultiCurrencyTransfer::findOrFail($id);
        return view('multi_currency.transfers.show', compact('transfer'));
    }

    public function store(Request $request)
    {
        // Validation and storing transfer logic
        $validated = $request->validate([
            'sender_wallet_id' => 'required|exists:wallet,id',
            'receiver_wallet_id' => 'required|exists:wallet,id',
            'amount' => 'required|numeric',
            'sender_currency' => 'required|string',
            'receiver_currency' => 'required|string',
            'conversion_rate' => 'required|numeric',
            'fee' => 'required|numeric',
            'converted_amount' => 'required|numeric',
        ]);

        $transfer = MultiCurrencyTransfer::create($validated);

        return redirect()->route('multi-currency.transfers.index')
                         ->with('success', 'Multi-currency transfer created successfully!');
    }

    public function confirmTransfer(Request $request)
    {
        // Logic to confirm the transfer (deducting from sender and adding to receiver)
        // Ensure to deduct the sender's balance and add to the receiver's balance
        $senderWallet = Wallet::find($request->sender_wallet_id);
        $receiverWallet = Wallet::find($request->receiver_wallet_id);

        if ($senderWallet->balance < $request->converted_amount) {
            return redirect()->route('multi-currency.transfers.index')
                             ->with('error', 'Insufficient funds in the sender\'s wallet!');
        }

        // Proceed with the transfer logic (transfer funds and update balances)
        $senderWallet->balance -= $request->converted_amount;
        $receiverWallet->balance += $request->converted_amount;

        $senderWallet->save();
        $receiverWallet->save();

        return redirect()->route('multi-currency.transfers.index')
                         ->with('success', 'Transfer confirmed and completed!');
    }
}
