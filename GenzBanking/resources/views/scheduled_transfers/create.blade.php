@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Schedule a Transfer</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

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

    <form action="{{ route('scheduled_transfers.store') }}" method="POST">
        @csrf

        <!-- Recipient -->
        <div class="mb-3">
            <label for="recipient_identifier" class="form-label">Recipient (Wallet ID or Phone Number)</label>
            <input type="text" name="recipient_identifier" id="recipient_identifier" class="form-control" 
                   placeholder="Enter Wallet ID or Phone Number" required>
        </div>

        <!-- Amount -->
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" 
                   placeholder="Enter amount" step="0.01" min="0.01" required>
        </div>

        <!-- Scheduled Date & Time -->
        <div class="mb-3">
            <label for="scheduled_at" class="form-label">Scheduled Date & Time</label>
            <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description (Optional)</label>
            <textarea name="description" id="description" class="form-control" 
                      placeholder="Enter description"></textarea>
        </div>

        <!-- Submit Button -->
        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-calendar-plus"></i> Schedule Transfer
            </button>
            <a href="{{ route('scheduled_transfers.index') }}" class="btn btn-outline-primary px-4">
                <i class="bi bi-list-ul"></i> View Scheduled
            </a>
        </div>
    </form>
</div>
@endsection