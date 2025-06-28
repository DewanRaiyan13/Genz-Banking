@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Loan Requests</h2>

    <!-- Loan Requests Table -->
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
                        <td>{{ number_format($loanRequest->amount, 2) }}</td>
                        <td>{{ $loanRequest->repayment_date }}</td>
                        <td>{{ ucfirst($loanRequest->status) }}</td>
                        <td>
                            @if($loanRequest->status === 'pending' && $loanRequest->borrower_id !== Auth::id())
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
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection