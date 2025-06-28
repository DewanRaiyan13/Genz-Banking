@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Transfer Funds</h5>
                </div>

                <div class="card-body">
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

                    <form method="POST" action="{{ route('transactions.transfer.submit') }}">
                        @csrf

                        <!-- Recipient -->
                        <div class="mb-3">
                            <label for="recipient" class="form-label">Recipient (Wallet ID or Phone Number)</label>
                            <input type="text" class="form-control @error('recipient') is-invalid @enderror" 
                                   id="recipient" name="recipient" value="{{ old('recipient') }}" required>
                            @error('recipient')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ auth()->user()->wallet->currency ?? 'USD' }}</span>
                                <input type="number" step="0.01" min="0.01" max="{{ auth()->user()->wallet->balance ?? 0 }}"
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount') }}" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">
                                Available balance: {{ auth()->user()->wallet->currency ?? 'USD' }} {{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}
                            </small>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Error Messages for Self-Transfer or Currency Mismatch -->
                        @if (session('error_self_transfer'))
                            <div class="alert alert-danger">
                                {{ session('error_self_transfer') }}
                            </div>
                        @endif

                        @if (session('error_currency_mismatch'))
                            <div class="alert alert-danger">
                                {{ session('error_currency_mismatch') }}
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Transfer Funds
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection