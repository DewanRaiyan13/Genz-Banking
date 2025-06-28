<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the currencies.
     */
    public function index()
    {
        $currencies = Currency::all();
        return view('admin.currencies.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new currency.
     */
    public function create()
    {
        return view('admin.currencies.create');
    }

    /**
     * Store a newly created currency in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:currencies,code|max:3', // ISO 4217 currency code
            'exchange_rate' => 'required|numeric|min:0', // Exchange rate must be a positive number
        ]);

        Currency::create([
            'code' => strtoupper($request->code), // Ensure the currency code is uppercase
            'exchange_rate' => $request->exchange_rate,
        ]);

        return redirect()->route('currencies.index')->with('success', 'Currency added successfully.');
    }

    /**
     * Show the form for editing the specified currency.
     */
    public function edit(Currency $currency)
    {
        return view('admin.currencies.edit', compact('currency'));
    }

    /**
     * Update the specified currency in the database.
     */
    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'exchange_rate' => 'required|numeric|min:0', // Only allow updating the exchange rate
        ]);

        $currency->update([
            'exchange_rate' => $request->exchange_rate,
        ]);

        return redirect()->route('currencies.index')->with('success', 'Currency updated successfully.');
    }

    /**
     * Remove the specified currency from the database.
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();

        return redirect()->route('currencies.index')->with('success', 'Currency deleted successfully.');
    }
}
