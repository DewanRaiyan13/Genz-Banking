@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Loan Request Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Submit a Loan Request</h4>
        </div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('loan_requests.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="lender_identifier" class="form-label">Lender (Wallet ID or Phone Number)</label>
                    <input type="text" name="lender_identifier" id="lender_identifier" class="form-control" placeholder="Enter Wallet ID or Phone Number" required>
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Loan Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter loan amount" required>
                </div>
                <div class="mb-3">
                    <label for="repayment_date" class="form-label">Repayment Date</label>
                    <input type="date" name="repayment_date" id="repayment_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="purpose" class="form-label">Purpose</label>
                    <textarea name="purpose" id="purpose" class="form-control" placeholder="Enter the purpose of the loan" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Loan Request</button>
            </form>
        </div>
    </div>

    <!-- Loan Requests Table -->
    <div class="card">
        <div class="card-header">
            <h4>Loan Requests</h4>
        </div>
        <div class="card-body">
            @if($loanRequests->isEmpty())
                <p>No loan requests found.</p>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Borrower</th>
                            <th>Lender</th>
                            <th>Amount</th>
                            <th>Repayment Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loanRequests as $loanRequest)
                            <tr>
                                <td>{{ $loanRequest->id }}</td>
                                <td>{{ $loanRequest->borrower->name }}</td>
                                <td>{{ $loanRequest->lender->name ?? 'N/A' }}</td>
                                <td>${{ number_format($loanRequest->amount, 2) }}</td>
                                <td>{{ $loanRequest->repayment_date }}</td>
                                <td>
                                    <span class="badge 
                                        @if($loanRequest->status === 'pending') bg-warning 
                                        @elseif($loanRequest->status === 'approved') bg-success 
                                        @else bg-danger 
                                        @endif">
                                        {{ ucfirst($loanRequest->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($loanRequest->status === 'pending' && $loanRequest->lender_id === Auth::id())
                                        <form action="{{ route('loan_requests.approve', $loanRequest->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                        <form action="{{ route('loan_requests.decline', $loanRequest->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                                        </form>
                                    @else
                                        <span class="text-muted">No actions available</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection