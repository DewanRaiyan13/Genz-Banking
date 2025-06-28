@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Passport Endorsement Request</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('passport_endorsements.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="passport_number" class="form-label">Passport Number</label>
            <input type="text" name="passport_number" id="passport_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="wallet_id" class="form-label">Select Wallet</label>
            <select name="wallet_id" id="wallet_id" class="form-control" required>
                @foreach ($wallets as $wallet)
                    <option value="{{ $wallet->id }}">{{ $wallet->name }} ({{ $wallet->formatted_balance }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="amount_usd" class="form-label">Amount in USD</label>
            <input type="number" name="amount_usd" id="amount_usd" class="form-control" step="0.01" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit Request</button>
    </form>

    <a href="{{ route('passport_endorsements.index') }}" class="btn btn-secondary mt-3">See Request Status</a>
</div>
@endsection