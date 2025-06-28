@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ $sharedWallet->name }}</h2>
        <p><strong>Description:</strong> {{ $sharedWallet->description ?? 'No description' }}</p>
        <p><strong>Balance:</strong> {{ number_format($sharedWallet->balance, 2) }}</p>

        <!-- Flash Messages -->
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

        @if (session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        <h3>Members</h3>
        <ul class="list-group">
            @foreach ($members as $member)
                <li class="list-group-item">
                    {{ $member->name }}
                </li>
            @endforeach
        </ul>

        @if ($sharedWallet->creator_id === Auth::id() || $sharedWallet->members->contains(Auth::id()))
            <hr>

            <!-- Add Funds to Wallet Form -->
            <form action="{{ route('shared_wallets.funds.add', $sharedWallet) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="amount">Add Funds</label>
                    <div class="input-group">
                        <input type="number" id="amount" name="amount" class="form-control" min="0.01" step="0.01" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success mt-3">Add Funds</button>
            </form>

            <!-- Add Member Form -->
            <form action="{{ route('shared_wallets.add_member', $sharedWallet) }}" method="POST" class="mt-3">
                @csrf
                <div class="form-group">
                    <label for="identifier">Add a Member</label>
                    <input 
                        type="text" 
                        id="identifier" 
                        name="identifier" 
                        class="form-control" 
                        placeholder="Enter wallet ID or phone number" 
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary mt-3">Add Member</button>
            </form>
        @else
            <p>You are not authorized to add funds or members to this wallet.</p>
        @endif

        <a href="{{ route('shared_wallets.index') }}" class="btn btn-secondary mt-3">Back to Wallets</a>
    </div>
@endsection