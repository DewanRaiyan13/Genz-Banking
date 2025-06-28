<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Currency;
use App\Models\MultiCurrencyTransfer;
use Illuminate\Http\Request;

class MultiCurrencyController extends Controller
{
    public function showTransferForm()
    {
        $wallets = Wallet::where('user_id', auth()->id())->get();
        $currencies = Currency::all()->keyBy('code'); // Fetch all currencies and index by code

        // Attach exchange rate and converted balance to each wallet
        foreach ($wallets as $wallet) {
            $currency = $currencies[$wallet->currency] ?? null;
            $wallet->exchange_rate = $currency ? $currency->exchange_rate : 1; // Default exchange rate to 1 if not found
            $wallet->converted_balance = $currency ? $wallet->balance / $currency->exchange_rate : $wallet->balance;
        }

        return view('currencies.transfer', compact('wallets', 'currencies'));
    }

    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'sender_wallet_id' => 'required|exists:wallet,id',
            'receiver_wallet_id' => 'required|exists:wallet,id|different:sender_wallet_id',
            'amount' => 'required|numeric|min:0.01',
            'conversion_rate' => 'required|numeric|min:0.01',
            'fee' => 'nullable|numeric|min:0',
            'converted_amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string'
        ]);

        $senderWallet = Wallet::find($request->sender_wallet_id);
        $receiverWallet = Wallet::find($request->receiver_wallet_id);

        if (!$senderWallet || !$receiverWallet) {
            return back()->with('error', 'Wallets not found.');
        }

        $senderCurrency = $senderWallet->currency;
        $receiverCurrency = $request->currency;

        // Calculate the 5% conversion fee
        $conversionFee = $request->amount * 0.05;

        // Check if the sender has sufficient balance (amount + fee)
        if (!$senderWallet->canTransfer($request->amount + $conversionFee)) {
            return back()->with('error', 'Insufficient balance to cover the transfer and conversion fee.');
        }

        // Deduct the amount and the fee from the sender's wallet
        $senderWallet->withdraw($request->amount + $conversionFee);

        // Deposit the converted amount into the receiver's wallet
        $receiverWallet->deposit($request->converted_amount);

        // Store the transfer details
        MultiCurrencyTransfer::create([
            'sender_wallet_id' => $senderWallet->id,
            'receiver_wallet_id' => $receiverWallet->id,
            'amount' => $request->amount,
            'sender_currency' => $senderCurrency,
            'receiver_currency' => $receiverCurrency,
            'conversion_rate' => $request->conversion_rate,
            'fee' => $conversionFee,
            'converted_amount' => $request->converted_amount,
        ]);

        return redirect()->route('multi-currency.transfer')->with('success', 'Transfer successful. A 5% conversion fee has been deducted.');
    }

    public function index()
    {
        $transfers = MultiCurrencyTransfer::all();
        $currencies = Currency::all()->keyBy('code'); // Fetch all currencies and index by code

        // Convert sender amounts to USD for display
        foreach ($transfers as $transfer) {
            $senderCurrency = $currencies[$transfer->sender_currency] ?? null;
            $transfer->converted_to_usd = $senderCurrency 
                ? $transfer->amount / $senderCurrency->exchange_rate 
                : $transfer->amount; // Default to the original amount if no exchange rate is found
        }

        return view('currencies.index', compact('transfers', 'currencies'));
    }
}
