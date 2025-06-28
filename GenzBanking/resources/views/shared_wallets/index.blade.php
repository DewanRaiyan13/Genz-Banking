@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Shared Wallets</h2>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($sharedWallets->isEmpty())
            <p>You are not a member of any shared wallets yet.</p>
        @else
            <div class="list-group">
                @foreach ($sharedWallets as $wallet)
                    <a href="{{ route('shared_wallets.show', $wallet) }}" class="list-group-item list-group-item-action">
                        <h5>{{ $wallet->name }}</h5>
                        <p>{{ $wallet->description ?? 'No description' }}</p>
                        <p>Balance: ${{ number_format($wallet->balance, 2) }}</p>
                    </a>
                @endforeach
            </div>
        @endif

        <a href="{{ route('shared_wallets.create') }}" class="btn btn-primary mt-3">Create New Shared Wallet</a>
    </div>
@endsection
