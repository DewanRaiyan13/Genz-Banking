<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Notifications\LoanRequestNotification;

class LoanRequestController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Fetch loan requests where the user is either the borrower or lender
        $loanRequests = LoanRequest::where('borrower_id', $userId)
            ->orWhere('lender_id', $userId)
            ->with(['borrower', 'lender'])
            ->get();

        return view('dashboard', compact('loanRequests'));
    }

    public function create()
    {
        $userId = Auth::id();

        // Fetch loan requests where the user is either the borrower or lender
        $loanRequests = LoanRequest::where('borrower_id', $userId)
            ->orWhere('lender_id', $userId)
            ->with(['borrower', 'lender'])
            ->get();

        return view('loan_requests.create', compact('loanRequests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lender_identifier' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'repayment_date' => 'required|date|after:today',
            'purpose' => 'required|string|max:255',
        ]);

        $borrowerWallet = Auth::user()->wallet;
        $lenderWallet = $this->findLenderWallet($validated['lender_identifier']);

        // Check if lender wallet exists
        if (!$lenderWallet) {
            return back()->with('error', 'Lender wallet not found.');
        }

        // Check if borrower is requesting a loan from themselves
        if ($borrowerWallet->id === $lenderWallet->id) {
            return back()->with('error', 'You cannot request a loan for yourself.');
        }

        // Check if borrower and lender have the same currency
        if ($borrowerWallet->currency !== $lenderWallet->currency) {
            return back()->with('error', 'Borrower and lender must have the same currency.');
        }

        // Create the loan request
        LoanRequest::create([
            'borrower_id' => Auth::id(),
            'lender_id' => $lenderWallet->user_id,
            'amount' => $validated['amount'],
            'repayment_date' => $validated['repayment_date'],
            'purpose' => $validated['purpose'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Loan request submitted successfully.');
    }

    public function approve($id)
    {
        $loanRequest = LoanRequest::findOrFail($id);

        // Ensure only the lender can approve the request
        if ($loanRequest->lender_id !== Auth::id()) {
            return redirect()->route('loan_requests.show', $id)->with('error', 'You are not authorized to approve this loan request.');
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Fetch lender and borrower wallets
            $lenderWallet = Wallet::where('user_id', $loanRequest->lender_id)->first();
            $borrowerWallet = Wallet::where('user_id', $loanRequest->borrower_id)->first();

            // Check if the lender has sufficient balance
            if ($lenderWallet->balance < $loanRequest->amount) {
                return redirect()->route('loan_requests.show', $id)->with('error', 'Insufficient balance in lender\'s wallet.');
            }

            // Deduct the amount from the lender's wallet
            $lenderWallet->balance -= $loanRequest->amount;
            $lenderWallet->save();

            // Add the amount to the borrower's wallet
            if ($borrowerWallet) {
                $borrowerWallet->balance += $loanRequest->amount;
                $borrowerWallet->save();
            } else {
                // If the borrower doesn't have a wallet, create one
                Wallet::create([
                    'user_id' => $loanRequest->borrower_id,
                    'balance' => $loanRequest->amount,
                ]);
            }

            // Update the loan request status to "approved"
            $loanRequest->update(['status' => 'approved']);

            // Commit the transaction
            DB::commit();

            // Notify the borrower
            $loanRequest->borrower->notify(new LoanRequestNotification($loanRequest, 'approved'));

            return redirect()->route('loan_requests.show', $id)->with('success', 'Loan request approved successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();
            return redirect()->route('loan_requests.show', $id)->with('error', 'An error occurred while processing the loan request.');
        }
    }

    public function decline($id)
    {
        $loanRequest = LoanRequest::findOrFail($id);

        // Ensure only the lender can decline the request
        if ($loanRequest->lender_id !== Auth::id()) {
            return redirect()->route('loan_requests.show', $id)->with('error', 'You are not authorized to decline this loan request.');
        }

        // Update the loan request status to "declined"
        $loanRequest->update(['status' => 'declined']);

        // Notify the borrower
        $loanRequest->borrower->notify(new LoanRequestNotification($loanRequest, 'declined'));

        return redirect()->route('loan_requests.show', $id)->with('success', 'Loan request declined successfully.');
    }

    public function show($id)
    {
        $loanRequest = LoanRequest::with(['borrower', 'lender'])->findOrFail($id);

        return view('loan_requests.show', compact('loanRequest'));
    }

    private function findLenderWallet($identifier)
    {
        // Try by wallet ID
        $wallet = Wallet::find($identifier);
        if ($wallet) return $wallet;

        // Try by phone number
        $user = User::where('phone', $identifier)->first();
        return $user?->wallet;
    }
}
