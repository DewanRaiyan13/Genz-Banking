<?php

namespace App\Http\Controllers;

use App\Models\SharedWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SharedWalletController extends Controller
{
    /**
     * Display a listing of the user's shared wallets.
     */
    public function index()
    {
        $sharedWallets = SharedWallet::where('creator_id', Auth::id())
            ->orWhereHas('members', function ($query) {
                $query->where('user_id', Auth::id());
            })->get();

        return view('shared_wallets.index', compact('sharedWallets'));
    }

    /**
     * Show the form for creating a new shared wallet.
     */
    public function create()
    {
        return view('shared_wallets.create');
    }

    /**
     * Store a newly created shared wallet.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $wallet = SharedWallet::create([
            'name' => $request->name,
            'creator_id' => Auth::id(),
            'description' => $request->description,
            'balance' => 0,
        ]);

        $wallet->members()->attach(Auth::id());

        return redirect()->route('shared_wallets.index')
            ->with('success', 'Shared wallet created successfully!');
    }

    /**
     * Display the details of a specific shared wallet.
     */
    public function show(SharedWallet $sharedWallet)
    {
        $this->authorize('view', $sharedWallet);

        // Fetch the members of the shared wallet
        $members = $sharedWallet->members;

        return view('shared_wallets.show', compact('sharedWallet', 'members'));
    }

    /**
     * Add a member to the shared wallet.
     */
    public function addMember(Request $request, SharedWallet $sharedWallet)
    {
        $request->validate([
            'identifier' => 'required', // Accept wallet ID or phone number
        ]);

        $this->authorize('update', $sharedWallet);

        // Find the user by wallet ID or phone number
        $user = null;

        // Check if the identifier is a wallet ID
        if (is_numeric($request->identifier)) {
            $user = \App\Models\User::whereHas('wallet', function ($query) use ($request) {
                $query->where('id', $request->identifier);
            })->first();
        }

        // If not found by wallet ID, check by phone number
        if (!$user) {
            $user = \App\Models\User::where('phone', $request->identifier)->first();
        }

        // If user is not found, return an error
        if (!$user) {
            return back()->with('error', 'No user found with the provided wallet ID or phone number.');
        }

        // Check if the user has a wallet
        $userWallet = $user->wallet;
        if (!$userWallet) {
            return back()->with('error', 'The user does not have a wallet.');
        }

        // Check if the user adding the member has a wallet
        $currentUserWallet = Auth::user()->wallet;
        if (!$currentUserWallet) {
            return back()->with('error', 'You do not have a wallet to perform this action.');
        }

        // Check if the currencies of the current user and the member match
        if ($currentUserWallet->currency !== $userWallet->currency) {
            return back()->with('error', 'You cannot add this member because their wallet currency does not match your wallet currency.');
        }

        // Check if the user is already a member
        if ($sharedWallet->members->contains($user->id)) {
            return back()->with('info', 'User is already a member of this wallet.');
        }

        // Add the user as a member
        $sharedWallet->members()->attach($user->id);

        // Redirect with a success message
        return redirect()->route('shared_wallets.show', $sharedWallet)
            ->with('success', 'Member added successfully!');
    }

    /**
     * Add funds to the shared wallet.
     */
    public function addFunds(Request $request, SharedWallet $sharedWallet)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $this->authorize('update', $sharedWallet);

        $sharedWallet->balance += $request->amount;
        $sharedWallet->save();

        // If you have a transactions relationship:
        if (method_exists($sharedWallet, 'transactions')) {
            $sharedWallet->transactions()->create([
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'type' => 'credit',
                'note' => 'Funds added to the wallet',
            ]);
        }

        return redirect()->route('shared_wallets.show', $sharedWallet)
            ->with('success', 'Funds added successfully!');
    }
}
