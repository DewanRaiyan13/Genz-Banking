<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Show the form to create a new wallet.
     */
    public function showCreateForm()
    {
        // Fetch all available currencies
        $currencies = Currency::all();

        // Return the create wallet view with the currencies
        return view('create', compact('currencies'));
    }

    /**
     * Handle the creation of a new wallet.
     */
    public function createWallet(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'name' => 'nullable|string|max:255', // Wallet name is optional
                'balance' => 'required|numeric|min:0', // Initial balance must be a positive number
                'currency' => 'required|string|size:3|exists:currencies,code', // Validate the selected currency
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Debug validation errors
            return back()->withErrors($e->errors())->withInput();
        }

        // Add the authenticated user's ID to the validated data
        $validated['user_id'] = Auth::id();

        // Create the wallet with the validated data
        Wallet::create($validated);

        // Redirect to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Wallet created successfully!');
    }

    /**
     * Store a new wallet (alternative method).
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'nullable|string|max:255', // Wallet name is optional
            'balance' => 'required|numeric|min:0', // Initial balance must be a positive number
            'currency' => 'required|string|size:3|exists:currencies,code', // Validate the selected currency
        ]);

        // Add the authenticated user's ID to the validated data
        $validated['user_id'] = Auth::id();

        // Create the wallet with the validated data
        Wallet::create($validated);

        // Redirect to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Wallet created successfully!');
    }
}
