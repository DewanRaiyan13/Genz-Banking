@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Wallet</h2>

    @if (session('message'))
        <div class="alert alert-warning">{{ session('message') }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('wallet.create.submit') }}" method="POST">
        @csrf
        <!-- Wallet Name -->
        <div class="form-group mb-3">
            <label for="wallet_name">Wallet Name (optional)</label>
            <input type="text" class="form-control" id="wallet_name" name="name" placeholder="My Wallet" required>
        </div>

        <!-- Balance -->
        <div class="form-group mb-3">
            <label for="balance">Balance</label>
            <input type="number" class="form-control" id="balance" name="balance" placeholder="0000" required>
        </div>

        <!-- Currency Selection -->
        <div class="form-group mb-4">
            <label for="currency">Currency</label>
            <select 
                name="currency" 
                id="currency" 
                class="form-control" 
                required
            >
                <option value="" disabled selected>Select currency</option>
                @foreach ($currencies as $currency)
                    <option 
                        value="{{ $currency->code }}"
                        {{ old('currency') === $currency->code ? 'selected' : '' }}
                    >
                        {{ $currency->code }} - {{ $currency->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Create Wallet</button>
    </form>
</div>
@endsection
