<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CardOfferController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ScheduledTransferController;
use App\Http\Controllers\MultiCurrencyController;
use App\Http\Controllers\MultiCurrencyTransferController;
use App\Http\Controllers\SharedWalletController;
use App\Http\Controllers\AtmController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\WalletStatementController;
use App\Http\Controllers\PassportEndorsementController;
use App\Http\Controllers\CardRequestController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Authenticated routes group
Route::middleware('auth')->group(function () {

    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Dashboard
    Route::get('/dashboard', [LoanRequestController::class, 'index'])->name('dashboard');

    // Wallet
    Route::get('/wallet/create', [WalletController::class, 'showCreateForm'])->name('wallet.create.form');
    Route::post('/wallet/create', [WalletController::class, 'createWallet'])->name('wallet.create.submit');

    // Card Offers
    Route::get('/card-offers', [CardOfferController::class, 'index'])->name('card_offers');

    // Transactions
    Route::get('/transfer', [TransactionController::class, 'showTransferForm'])->name('transactions.transfer');
    Route::post('/transfer', [TransactionController::class, 'transfer'])->name('transactions.transfer.submit');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}/receipt', [TransactionController::class, 'generateReceipt'])->name('transactions.receipt');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

    // Notifications
    Route::patch('/notifications/{id}/mark-as-read', function ($id) {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return back();
    })->name('notifications.markAsRead');

    // Loan Requests
    Route::get('/loan-requests/create', [LoanRequestController::class, 'create'])->name('loan_requests.create');
    Route::get('/loan-requests', [LoanRequestController::class, 'index'])->name('loan_requests.index');
    Route::post('/loan-requests', [LoanRequestController::class, 'store'])->name('loan_requests.store');
    Route::patch('/loan-requests/{id}/approve', [LoanRequestController::class, 'approve'])->name('loan_requests.approve');
    Route::patch('/loan-requests/{id}/decline', [LoanRequestController::class, 'decline'])->name('loan_requests.decline');
    Route::get('/loan-requests/{id}', [LoanRequestController::class, 'show'])->name('loan_requests.show');

    // Scheduled Transfers
    Route::resource('scheduled_transfers', ScheduledTransferController::class)->except(['show']);

    // Admin Currencies
    Route::prefix('admin')->group(function () {
        Route::resource('currencies', CurrencyController::class)->except(['show']);
    });

    // Multi-currency
    Route::prefix('multi-currency')->group(function () {
        Route::get('/convert', [MultiCurrencyController::class, 'showConversionForm'])->name('multi-currency.convert');
        Route::post('/convert', [MultiCurrencyController::class, 'convert'])->name('multi-currency.convert.submit');
        Route::get('/transfer', [MultiCurrencyController::class, 'showTransferForm'])->name('multi-currency.transfer');
        Route::post('/transfer', [MultiCurrencyController::class, 'transfer'])->name('multi-currency.transfer.submit');
    });

    // Multi-currency Transfers
    Route::get('/multi-currency-transfers', [MultiCurrencyTransferController::class, 'index'])->name('multi-currency.transfers.index');
    Route::get('/multi-currency-transfers/{id}', [MultiCurrencyTransferController::class, 'show'])->name('multi-currency.transfers.show');
    Route::post('/multi-currency-transfers', [MultiCurrencyTransferController::class, 'store'])->name('multi-currency.transfers.store');
    Route::post('/multi-currency-transfers/confirm', [MultiCurrencyTransferController::class, 'confirmTransfer'])->name('multi-currency.transfers.confirm');

    // Shared Wallets
    Route::prefix('shared-wallets')->group(function () {
        Route::get('/', [SharedWalletController::class, 'index'])->name('shared_wallets.index');
        Route::get('/create', [SharedWalletController::class, 'create'])->name('shared_wallets.create');
        Route::post('/', [SharedWalletController::class, 'store'])->name('shared_wallets.store');
        Route::get('/{sharedWallet}', [SharedWalletController::class, 'show'])->name('shared_wallets.show');
        Route::post('/{sharedWallet}/members', [SharedWalletController::class, 'addMember'])->name('shared_wallets.members.add');
        Route::post('/{sharedWallet}/funds', [SharedWalletController::class, 'addFunds'])->name('shared_wallets.funds.add'); // <- FIXED route name
        Route::post('/{sharedWallet}/contribute', [SharedWalletController::class, 'contribute'])->name('shared_wallets.contribute');
        Route::post('/{sharedWallet}/add-member', [SharedWalletController::class, 'addMember'])->name('shared_wallets.add_member');
    });

    // Wallet Statements
    Route::get('/wallets/{walletId}/statement', [WalletStatementController::class, 'index'])->name('wallets.statement');
    Route::get('/wallets/{walletId}/statement/download', [WalletStatementController::class, 'downloadPdf'])->name('wallets.statement.download');

    // Passport Endorsements
    Route::get('/passport-endorsements/create', [PassportEndorsementController::class, 'create'])->name('passport_endorsements.create');
    Route::post('/passport-endorsements', [PassportEndorsementController::class, 'store'])->name('passport_endorsements.store');
    Route::get('/passport-endorsements', [PassportEndorsementController::class, 'index'])->name('passport_endorsements.index');

    // Card Requests
    Route::get('/card-requests/create', [CardRequestController::class, 'create'])->name('card_requests.create');
    Route::post('/card-requests', [CardRequestController::class, 'store'])->name('card_requests.store');
    Route::get('/card-requests/status', [CardRequestController::class, 'index'])->name('card_requests.status');
});

// Time Check (outside auth group)
Route::get('/time-check', function () {
    return [
        'APP_TIMEZONE' => config('app.timezone'),
        'PHP Time' => now()->toDateTimeString(),
        'DB Time' => \DB::select('SELECT NOW() as now')[0]->now,
        'Server Time' => shell_exec('date'),
        'Scheduled Transfers Pending' => \App\Models\ScheduledTransfer::where('status', 'pending')
            ->where('scheduled_at', '<=', now())
            ->count(),
    ];
});



Route::middleware(['auth'])->group(function () {
    Route::get('/shared-wallets', [SharedWalletController::class, 'index'])->name('shared_wallets.index');
    Route::get('/shared-wallets/create', [SharedWalletController::class, 'create'])->name('shared_wallets.create');
    Route::post('/shared-wallets', [SharedWalletController::class, 'store'])->name('shared_wallets.store');
    Route::get('/shared-wallets/{sharedWallet}', [SharedWalletController::class, 'show'])->name('shared_wallets.show');
    // Add inside auth middleware group
    Route::get('/nearby-atms', [AtmController::class, 'index'])->name('atms.nearby');
    Route::get('/withdraw', [WithdrawController::class, 'showForm'])->name('withdraw');
    Route::post('/withdraw', [WithdrawController::class, 'process'])->name('withdraw.submit');
    // Add inside auth middleware group
    Route::get('/withdraw', [WithdrawController::class, 'showForm'])->name('withdraw');
    Route::post('/withdraw', [WithdrawController::class, 'process'])->name('withdraw.submit');
});
