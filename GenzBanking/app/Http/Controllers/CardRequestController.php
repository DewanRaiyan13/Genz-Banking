<?php

namespace App\Http\Controllers;

use App\Models\CardRequest;
use App\Models\Wallet;
use App\Models\CardOffer; // Assuming card types are stored in a CardOffer model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardRequestController extends Controller
{
    public function create()
    {
        $wallets = Wallet::where('user_id', Auth::id())->get();
        $cardTypes = CardOffer::all(); // Fetch card types from the database
        return view('card_requests.create', compact('wallets', 'cardTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_on_card' => 'required|string|max:255',
            'card_type' => 'required|string|exists:card_offers,name', // Ensure card type exists in the database
            'wallet_id' => 'required|exists:wallet,id',
            'spending_limit' => 'required|numeric|min:0.01',
            'tin_number' => 'required|string|max:20',
        ]);

        // Create the card request
        CardRequest::create([
            'user_id' => Auth::id(),
            'wallet_id' => $request->wallet_id,
            'name_on_card' => $request->name_on_card,
            'card_type' => $request->card_type,
            'spending_limit' => $request->spending_limit,
            'tin_number' => $request->tin_number,
            'status' => 'pending',
        ]);

        return redirect()->route('card_requests.status')->with('success', 'Card request submitted successfully.');
    }

    public function index()
    {
        // Fetch only the logged-in user's card requests
        $cardRequests = CardRequest::where('user_id', Auth::id())->get();

        return view('card_requests.status', compact('cardRequests'));
    }
}
