@extends('layouts.app')

@section('content')
<!-- Animate.css + Bootstrap Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #f4f6f9;
    }

    .sidebar {
        background: linear-gradient(180deg, #4e73df, #224abe);
        color: #fff;
        min-height: 100vh;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
    }

    .sidebar .nav-link {
        color: #ffffff;
        font-weight: 500;
        padding: 14px 20px;
        transition: all 0.3s ease;
    }

    .sidebar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.15);
        border-left: 4px solid #ffc107;
        padding-left: 16px;
    }

    .sidebar .nav-link i {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .dashboard-header {
        background: #ffffff;
        padding: 2rem 1.5rem;
        border-radius: 12px;
        margin-top: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    }

    .dashboard-header h1 {
        color: #2c3e50;
        font-weight: 600;
    }

    .dashboard-header p {
        color: #6c757d;
    }

    .wallet-section {
        margin-top: 30px;
        animation: fadeInUp 0.7s ease-in-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .wallet-info {
        animation: fadeInUp 1s ease-in-out;
    }

    .wallet-info > div {
        margin-bottom: 20px;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }

    .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }
</style>

<div class="container-fluid animate__animated animate__fadeIn">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('wallet.create.form') }}">
                            <i class="bi bi-plus-circle"></i> Create Wallet
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('card_offers') }}">
                            <i class="bi bi-credit-card"></i> See Card Offers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions.transfer') }}">
                            <i class="bi bi-arrow-left-right"></i> Transfer Money
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions.index') }}">
                            <i class="bi bi-list"></i> View Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('loan_requests.create') }}">
                            <i class="bi bi-cash-stack"></i> Request / Approve Loan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('scheduled_transfers.index') }}">
                            <i class="bi bi-calendar-check"></i> Scheduled Transfers
                        </a>
                        <li class="nav-item">
                        <a class="nav-link" href="{{ route('multi-currency.transfer') }}">
                            <i class="bi bi-currency-exchange"></i> Multi-Currency Transfer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shared_wallets.index') }}">
                            <i class="bi bi-calendar-check"></i> Manage Shared Wallets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('atms.nearby') }}">
                            <i class="bi bi-credit-card"></i> Nearby ATMs
                        </a>
                    </li>
                    <li class="nav-item">
                        @if (Auth::user()->wallet)
                            <a class="nav-link" href="{{ route('wallets.statement', Auth::user()->wallet->id) }}">
                                <i class="bi bi-file-earmark-text"></i> Wallet Statement
                            </a>
                        @else
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                                <i class="bi bi-file-earmark-text"></i> Wallet Statement (Unavailable)
                            </a>
                        @endif
                    </li>
                    <li class="nav-item">
                        @if (Auth::user()->wallet)
                            <a class="nav-link" href="{{ route('passport_endorsements.create') }}">
                                <i class="bi bi-file-earmark-text"></i> Passport Endorsement
                            </a>
                        @else
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                                <i class="bi bi-file-earmark-text"></i> Passport Endorsement (Unavailable)
                            </a>
                        @endif
                    </li>
                    <li class="nav-item">
                        @php
                            // Fetch card requests for the logged-in user
                            $cardRequests = \App\Models\CardRequest::where('user_id', Auth::id())->get();
                        @endphp
                        @if ($cardRequests->isNotEmpty())
                            <a class="nav-link" href="{{ route('card_requests.status') }}">
                                <i class="bi bi-file-earmark-text"></i>> Card Request Status
                            </a>
                        @else
                            <a class="nav-link" href="#">
                                <i class="bi bi-file-earmark-text"></i>> Card Request Status (Unavailable)
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="dashboard-header animate__animated animate__fadeInDown">
                <h1 class="h2">Welcome to Your Dashboard 🎉</h1>
                <p class="mb-0">Select an option from the sidebar to get started with your wallet.</p>
            </div>

            <!-- Wallet Info -->
            <div class="wallet-section">
                <div class="wallet-info">
                    <!-- Wallet Holder -->
                    <div class="animate__animated animate__fadeInLeft">
                        <strong>Wallet Holder:</strong> {{ Auth::user()->name }}
                    </div>

                    <!-- Wallet ID -->
                    <div class="animate__animated animate__fadeInLeft animate__delay-1s">
                        <strong>Wallet ID:</strong> {{ Auth::user()->wallet ? Auth::user()->wallet->id : 'Not Available' }}
                    </div>

                    <!-- Wallet Balance -->
                    <div class="animate__animated animate__fadeInLeft animate__delay-2s">
                        @php
                            $wallet = Auth::user()->wallet;
                            $currencySymbols = [
                                'USD' => '$',
                                'EUR' => '€',
                                'GBP' => '£',
                                'BDT' => '৳',
                                'INR' => '₹',
                                'JPY' => '¥',
                                'CAD' => 'C$',
                                'AUD' => 'A$',
                                'CNY' => '¥',
                                'KRW' => '₩',
                                // Add more currencies if needed
                            ];
                            $symbol = $wallet ? ($currencySymbols[$wallet->currency] ?? $wallet->currency) : '';
                        @endphp

                        <strong>Balance:</strong> 
                        {{ $wallet ? $symbol . number_format($wallet->balance, 2) . " ({$wallet->currency})" : 'No Wallet Created' }}
                    </div>
                </div>
            </div>

            <!-- Notifications Section -->
            <div class="notifications-section">
                <h4>Loan Notifications</h4>

                <!-- Display Pending Loan Requests for Lenders -->
                @php
                    $pendingLoanRequests = \App\Models\LoanRequest::where('lender_id', Auth::id())
                        ->where('status', 'pending')
                        ->with('borrower')
                        ->get();
                @endphp

                @if ($pendingLoanRequests->isNotEmpty())
                    <div class="alert alert-warning">
                        <strong>You have pending loan requests:</strong>
                        <ul>
                            @foreach ($pendingLoanRequests as $loanRequest)
                                <li>
                                    Loan Request from <strong>{{ $loanRequest->borrower->name }}</strong> for 
                                    <strong>${{ number_format($loanRequest->amount, 2) }}</strong> 
                                    (Repayment Date: {{ $loanRequest->repayment_date }}).
                                    <a href="{{ route('loan_requests.show', $loanRequest->id) }}" class="btn btn-sm btn-primary">View Request</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Display General Notifications -->
                @forelse (Auth::user()->unreadNotifications as $notification)
                    <div class="alert alert-info">
                        {{ $notification->data['message'] ?? 'No message available.' }}
                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-link">Mark as Read</button>
                        </form>
                        @if (isset($notification->data['loan_request_id']))
                            <a href="{{ route('loan_requests.show', $notification->data['loan_request_id']) }}" class="btn btn-sm btn-primary">View Loan Request</a>
                        @endif
                    </div>
                @empty
                    <p>No new notifications.</p>
                @endforelse
            </div>
        </main>
    </div>
</div>
@endsection