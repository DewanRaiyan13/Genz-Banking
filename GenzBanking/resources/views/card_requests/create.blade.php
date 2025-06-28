<!-- filepath: d:\Banking Website\GenzBanking\resources\views\card_requests\create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Request a Card</h2>

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

    <form action="{{ route('card_requests.store') }}" method="POST">
        @csrf

        <!-- Name on Card -->
        <div class="form-group mb-3">
            <label for="name_on_card">Name on Card</label>
            <input type="text" name="name_on_card" id="name_on_card" class="form-control" required>
        </div>

        <!-- Card Type -->
        <div class="form-group mb-3">
            <label for="card_type">Card Type</label>
            <select name="card_type" id="card_type" class="form-control" required>
                <option value="" disabled selected>Select card type</option>
                @foreach ($cardTypes as $cardType)
                    <option value="{{ $cardType->name }}">{{ $cardType->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Wallet -->
        <div class="form-group mb-3">
            <label for="wallet_id">Select Wallet</label>
            <select name="wallet_id" id="wallet_id" class="form-control" required>
                <option value="" disabled selected>Select wallet</option>
                @foreach ($wallets as $wallet)
                    <option value="{{ $wallet->id }}">{{ $wallet->name }} ({{ $wallet->formatted_balance }})</option>
                @endforeach
            </select>
        </div>

        <!-- Spending Limit -->
        <div class="form-group mb-3">
            <label for="spending_limit">Spending Limit</label>
            <input type="number" name="spending_limit" id="spending_limit" class="form-control" step="0.01" required>
        </div>

        <!-- TIN Number -->
        <div class="form-group mb-3">
            <label for="tin_number">TIN Number</label>
            <input type="text" name="tin_number" id="tin_number" class="form-control" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit Request</button>
    </form>
</div>
@endsection