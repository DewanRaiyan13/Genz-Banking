@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Multi-Currency Transfer</h2>

    {{-- Success & Error Feedback --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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

    {{-- Form --}}
    <form action="{{ route('multi-currency.transfer.submit') }}" method="POST">
        @csrf

        {{-- Sender Wallet --}}
        <div class="mb-3">
            <label for="sender_wallet_id" class="form-label">Sender Wallet</label>
            <select name="sender_wallet_id" id="sender_wallet_id" class="form-control" required>
                @foreach ($wallets as $wallet)
                    <option value="{{ $wallet->id }}" 
                        {{ old('sender_wallet_id') == $wallet->id ? 'selected' : '' }}>
                        {{ $wallet->name }} (Balance: ${{ number_format($wallet->balance, 2) }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Hidden Sender Currency --}}
        <input type="hidden" name="sender_currency" value="{{ old('sender_currency') }}">

        {{-- Amount Input --}}
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

        {{-- Currency Selection --}}
        <div class="mb-3">
            <label for="currency" class="form-label">Convert To</label>
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

        {{-- Converted Balance Display --}}
        <div class="mb-3">
            <label class="form-label">Converted Balance</label>
            <input type="text" id="converted_balance" class="form-control" readonly>
        </div>

        {{-- Fee Display --}}
        <div class="mb-3">
            <label class="form-label">5% Conversion Fee</label>
            <input type="text" id="conversion_fee" class="form-control" readonly>
        </div>

        {{-- Receiver --}}
        <div class="mb-3">
            <label for="receiver_identifier" class="form-label">Receiver Wallet ID or Phone</label>
            <input 
                type="text" 
                name="receiver_wallet_id" 
                id="receiver_identifier" 
                class="form-control" 
                placeholder="Enter wallet ID or phone number"
                value="{{ old('receiver_identifier') }}"
                required
            >
        </div>

        {{-- Hidden conversion_rate and converted_amount --}}
        <input type="hidden" name="conversion_rate" id="conversion_rate" value="">
        <input type="hidden" name="converted_amount" id="converted_amount" value="">

        <button type="submit" class="btn btn-primary">Transfer</button>
    </form>
</div>

{{-- JavaScript for calculating conversion and fee --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const amountInput = document.getElementById('amount');
        const currencySelect = document.getElementById('currency');
        const exchangeRates = @json($currencies->pluck('exchange_rate', 'code'));

        function calculateConversion() {
            const amount = parseFloat(amountInput.value) || 0;
            const selectedCurrency = currencySelect.value;

            if (selectedCurrency && exchangeRates[selectedCurrency]) {
                const converted = amount * exchangeRates[selectedCurrency];
                const fee = converted * 0.05;

                document.getElementById('converted_balance').value = `${converted.toFixed(2)} ${selectedCurrency}`;
                document.getElementById('conversion_fee').value = `${fee.toFixed(2)} ${selectedCurrency}`;

                // Set hidden fields for conversion rate and converted amount
                document.getElementById('conversion_rate').value = exchangeRates[selectedCurrency];
                document.getElementById('converted_amount').value = converted;
            } else {
                document.getElementById('converted_balance').value = '';
                document.getElementById('conversion_fee').value = '';
                document.getElementById('conversion_rate').value = '';
                document.getElementById('converted_amount').value = '';
            }
        }

        amountInput.addEventListener('input', calculateConversion);
        currencySelect.addEventListener('change', calculateConversion);

        // Auto-calculate on load if values are present
        calculateConversion();
    });
</script>
@endsection
