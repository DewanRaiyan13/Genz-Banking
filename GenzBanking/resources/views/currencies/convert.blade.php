@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Currency Conversion</h2>

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-warning">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p><strong>Current Balance:</strong> ${{ number_format($wallet->balance, 2) }}</p>

    <form action="{{ route('multi-currency.convert.submit') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input 
                type="number" 
                name="amount" 
                id="amount" 
                class="form-control" 
                placeholder="Enter amount" 
                value="{{ old('amount') }}"
                step="0.01"
                min="0.01"
                required
            >
        </div>

        <div class="mb-3">
            <label for="currency" class="form-label">Target Currency</label>
            <select name="currency" id="currency" class="form-control" required>
                <option value="">-- Select Currency --</option>
                @foreach ($currencies as $currency)
                    <option value="{{ $currency->code }}" 
                        {{ old('currency') === $currency->code ? 'selected' : '' }}>
                        {{ $currency->code }} (Rate: {{ $currency->exchange_rate }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Convert</button>
    </form>
</div>
@endsection
