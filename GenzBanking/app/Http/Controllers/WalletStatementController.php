<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class WalletStatementController extends Controller
{
    public function index($walletId)
    {
        $wallet = Wallet::find($walletId);

        if (!$wallet) {
            abort(404, 'Wallet not found');
        }

        $transactions = $wallet->allTransactions();

        // Debugging output
        

        return view('wallets.statement', compact('wallet', 'transactions'));
    }

    public function downloadPdf($walletId)
    {
        $wallet = Wallet::findOrFail($walletId); // Ensure the wallet is loaded
        $transactions = $wallet->allTransactions(); // Fetch all transactions

        // Debugging: Check if the currency is loaded
        logger('Wallet Currency:', ['currency' => $wallet->currency]);

        $pdf = Pdf::loadView('wallets.statement_pdf', compact('wallet', 'transactions'));
        return $pdf->download('wallet_statement_' . $wallet->id . '.pdf');
    }
}