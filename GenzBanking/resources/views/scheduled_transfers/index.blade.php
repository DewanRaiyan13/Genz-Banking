@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Your Scheduled Transfers</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('scheduled_transfers.create') }}" class="btn btn-primary">Schedule a New Transfer</a>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Recipient</th>
                <th>Amount</th>
                <th>Scheduled At</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($scheduledTransfers as $transfer)
                <tr>
                    <td>{{ $transfer->recipient->name }}</td>
                    <td>${{ number_format($transfer->amount, 2) }}</td>
                    <td>{{ $transfer->scheduled_at }}</td>
                    <td>{{ ucfirst($transfer->status) }}</td>
                    <td>
                        @if ($transfer->status === 'pending')
                            <form action="{{ route('scheduled_transfers.destroy', $transfer->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No scheduled transfers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection