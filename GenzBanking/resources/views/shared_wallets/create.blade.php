@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create a New Shared Wallet</h2>
        
        <form action="{{ route('shared_wallets.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Wallet Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="description">Description (Optional)</label>
                <textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary mt-3">Create Wallet</button>
        </form>
    </div>
<div class="container">
    <h2>Create Shared Wallet</h2>
    <form method="POST" action="{{ route('shared_wallets.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Wallet Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
            <label for="name" class="form-label">Balance</label>
            <input type="text" class="form-control" id="balance" name="balance" required>
        </div>
        <button type="submit" class="btn btn-success">Create Wallet</button>
    </form>
</div>
@endsection
