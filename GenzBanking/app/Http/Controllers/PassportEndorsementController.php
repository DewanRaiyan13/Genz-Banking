<?php

namespace App\Http\Controllers;

use App\Models\PassportEndorsement;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassportEndorsementController extends Controller
{
    public function create()
    {
        $wallets = Wallet::where('user_id', Auth::id())->get();
        return view('passport_endorsements.create', compact('wallets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'passport_number' => 'required|string|max:20',
            'wallet_id' => 'required|exists:wallet,id',
            'amount_usd' => 'required|numeric|min:0.01',
        ]);

        $wallet = Wallet::findOrFail($request->wallet_id);

        // Conversion logic
        $conversionRate = 1; // Default for USD
        if ($wallet->currency !== 'USD') {
            $conversionRates = [
                'BDT' => 0.0093, // Example conversion rate
                'EUR' => 1.1,
                'INR' => 0.012,
            ];
            $conversionRate = $conversionRates[$wallet->currency] ?? 1;
        }

        $requiredBalance = $request->amount_usd / $conversionRate;

        if ($wallet->balance < $requiredBalance) {
            return back()->withErrors(['amount_usd' => 'Insufficient balance in your wallet.']);
        }

        // Create the endorsement request
        PassportEndorsement::create([
            'user_id' => Auth::id(),
            'wallet_id' => $wallet->id,
            'passport_number' => $request->passport_number,
            'amount_usd' => $request->amount_usd,
            'status' => 'pending',
        ]);

        return redirect()->route('passport_endorsements.index')->with('success', 'Passport endorsement request submitted successfully.');
    }

    public function index()
    {
        $endorsements = PassportEndorsement::with(['wallet'])
            ->where('user_id', auth()->id()) // Fetch only the logged-in user's data
            ->get();

        return view('passport_endorsements.show_status', compact('endorsements'));
    }
}