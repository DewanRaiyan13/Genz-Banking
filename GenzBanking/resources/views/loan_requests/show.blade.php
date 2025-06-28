@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Loan Request Details</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Loan Request #{{ $loanRequest->id }}</h5>
            <p><strong>Borrower:</strong> {{ $loanRequest->borrower->name }}</p>
            <p><strong>Lender:</strong> {{ $loanRequest->lender->name ?? 'N/A' }}</p>
            <p><strong>Amount:</strong> ${{ number_format($loanRequest->amount, 2) }}</p>
            <p><strong>Repayment Date:</strong> {{ $loanRequest->repayment_date }}</p>
            <p><strong>Status:</strong> 
                <span class="badge 
                    @if($loanRequest->status === 'pending') bg-warning 
                    @elseif($loanRequest->status === 'approved') bg-success 
                    @else bg-danger 
                    @endif">
                    {{ ucfirst($loanRequest->status) }}
                </span>
            </p>
            <p><strong>Purpose:</strong> {{ $loanRequest->purpose }}</p>

            <!-- Approve/Decline Buttons -->
            @if ($loanRequest->status === 'pending' && $loanRequest->lender_id === Auth::id())
                <form action="{{ route('loan_requests.approve', $loanRequest->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
                <form action="{{ route('loan_requests.decline', $loanRequest->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">Decline</button>
                </form>
            @endif
        </div>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
@endsection