@extends('layouts.app')

@section('content')

<!-- Animate.css CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="container mt-5 animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">💸 Your Transactions</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    @if($transactions->isEmpty())
        <div class="alert alert-info">
            No transactions found.
        </div>
    @else

        {{-- Regular Transactions --}}
        <div class="card mb-5 shadow-sm animate__animated animate__fadeInUp">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📘 Regular Transactions</h5>
            </div>
            <div class="card-body p-0">
                @if(!$transactions->isEmpty())
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Sender</th>
                                    <th>Receiver</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>{{ $transaction->senderWallet->user->name ?? 'N/A' }}</td>
                                        <td>{{ $transaction->receiverWallet->user->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($transaction->amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ ucfirst($transaction->status) }}</span>
                                        </td>
                                        <td>{{ $transaction->description ?? 'N/A' }}</td>
                                        <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('transactions.receipt', $transaction->id) }}" class="btn btn-sm btn-primary">
                                                Download Receipt
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-3">No regular transactions found.</div>
                @endif
            </div>
        </div>

    @endif
</div>

@endsection
