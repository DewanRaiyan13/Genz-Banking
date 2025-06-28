<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\ATM;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    // Show withdrawal form
    public function showForm(Request $request)
    {
        $atmId = $request->query('atm_id');
        $atm = ATM::find($atmId); // Or fetch from API if using OpenStreetMap
        
        return view('withdraw.form', [
            'atm' => $atm,
            'balance' => Auth::user()->wallet->balance ?? 0
        ]);
    }

    // Process withdrawal
    public function process(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100|max:20000', // Min 100, Max 20,000
            'atm_id' => 'required'
        ]);

        $user = Auth::user();
        $wallet = $user->wallet;
        $amount = $request->amount;

        // Check sufficient balance
        if (!$wallet || !$wallet->canTransfer($amount)) {
            return back()->with('error', 'Insufficient balance!');
        }

        // Create withdrawal transaction
        try {
            DB::transaction(function () use ($wallet, $amount, $user) {
                $wallet->withdraw($amount);
                
                Transaction::create([
                    'sender_wallet_id' => $wallet->id,
                    'receiver_wallet_id' => null, // Cash withdrawal
                    'amount' => $amount,
                    'transaction_id' => Transaction::generateTransactionId(),
                    'status' => 'completed',
                    'description' => 'ATM Cash Withdrawal',
                    'executed_at' => now()
                ]);
            });

            return redirect()->route('atms.nearby')
                ->with('success', "Success! Collect your cash from the ATM.");

        } catch (\Exception $e) {
            return back()->with('error', 'Withdrawal failed: ' . $e->getMessage());
        }
    }
}
