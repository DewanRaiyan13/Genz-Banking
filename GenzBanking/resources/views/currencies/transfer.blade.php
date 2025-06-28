@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <h2 class="text-primary mb-4">🌍 Multi-Currency Transfer</h2>

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

            <form action="{{ route('multi-currency.transfer') }}" method="POST" class="needs-validation">
                @csrf

                {{-- Sender Wallet --}}
                <div class="mb-3">
                    <label for="sender_wallet_id" class="form-label fw-semibold text-secondary">💳 Sender Wallet</label>
                    <select name="sender_wallet_id" id="sender_wallet_id" class="form-select border-primary shadow-sm" required>
                        <option value="" disabled selected>Select Wallet</option>
                        @foreach ($wallets as $wallet)
                            <option 
                                value="{{ $wallet->id }}" 
                                data-balance="{{ $wallet->balance }}" 
                                data-currency="{{ $wallet->currency }}" 
                                data-exchange-rate="{{ $wallet->exchange_rate }}">
                                {{ $wallet->name }} - {{ $wallet->id }} (💰 {{ $wallet->balance }} {{ $wallet->currency }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Amount Input --}}
                <div class="mb-3">
                    <label for="amount" class="form-label fw-semibold text-secondary">💵 Amount</label>
                    <input 
                        type="number" 
                        name="amount" 
                        id="amount" 
                        class="form-control border-success shadow-sm" 
                        placeholder="Enter amount" 
                        value="{{ old('amount') }}"
                        step="0.01" 
                        min="0.01" 
                        required
                    >
                </div>

                {{-- Convert To --}}
                <div class="mb-3">
                    <label for="convert_to_currency" class="form-label fw-semibold text-secondary">🔁 Convert To</label>
                    <select name="convert_to_currency" id="convert_to_currency" class="form-select border-info shadow-sm" required>
                        <option value="" disabled selected>Select Currency</option>
                        @foreach ($currencies as $currency)
                            <option 
                                value="{{ $currency->code }}" 
                                data-exchange-rate="{{ $currency->exchange_rate }}">
                                {{ $currency->code }} (Rate: {{ $currency->exchange_rate }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Converted Balance Display --}}
                <div class="mb-3">
                    <label for="converted_balance" class="form-label text-success">✅ Receiver Will Get</label>
                    <input type="text" id="converted_balance" class="form-control bg-light text-success fw-bold" readonly>
                </div>

                {{-- Fee Display --}}
                <div class="mb-3">
                    <label for="conversion_fee" class="form-label text-danger">💸 Conversion Fee (5%)</label>
                    <input type="text" id="conversion_fee" class="form-control bg-light text-danger fw-bold" readonly>
                </div>

                {{-- Hidden Inputs --}}
                <input type="hidden" name="conversion_rate" id="conversion_rate">
                <input type="hidden" name="converted_amount" id="hidden_converted_balance">
                <input type="hidden" name="currency" id="hidden_currency">

                {{-- Receiver --}}
                <div class="mb-3">
                    <label for="receiver_wallet_id" class="form-label fw-semibold text-secondary">📲 Receiver Wallet ID / Phone</label>
                    <input 
                        type="text" 
                        name="receiver_wallet_id" 
                        id="receiver_wallet_id" 
                        class="form-control border-warning shadow-sm" 
                        placeholder="Enter wallet ID or phone number"
                        value="{{ old('receiver_wallet_id') }}"
                        required
                    >
                </div>

                {{-- Conversion Summary --}}
                <div id="conversion_summary" class="card bg-light border-0 shadow-sm mb-4" style="display: none;">
                    <div class="card-body">
                        <h5 class="card-title text-primary">📊 Conversion Summary</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Exchange Rate:</span>
                                <strong><span id="summary_exchange_rate">1 USD = 1.000000 USD</span></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Amount to Receive:</span>
                                <strong class="text-success"><span id="summary_amount_to_receive">0.00 USD</span></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Conversion Fee (5%):</span>
                                <strong class="text-danger"><span id="summary_conversion_fee">0.00 USD</span></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total Deducted (Sender):</span>
                                <strong class="text-danger fw-bold"><span id="summary_total_deducted">0.00 USD</span></strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                        🚀 Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const senderWalletSelect = document.getElementById('sender_wallet_id');
        const convertToCurrencySelect = document.getElementById('convert_to_currency');
        const amountInput = document.getElementById('amount');
        const receiverInput = document.getElementById('receiver_wallet_id');

        const convertedBalanceInput = document.getElementById('converted_balance');
        const conversionFeeInput = document.getElementById('conversion_fee');
        const hiddenConvertedBalanceInput = document.getElementById('hidden_converted_balance');
        const hiddenConversionRateInput = document.getElementById('conversion_rate');
        const hiddenCurrencyInput = document.getElementById('hidden_currency');

        const conversionSummary = document.getElementById('conversion_summary');
        const summaryExchangeRate = document.getElementById('summary_exchange_rate');
        const summaryAmountToReceive = document.getElementById('summary_amount_to_receive');
        const summaryConversionFee = document.getElementById('summary_conversion_fee');
        const summaryTotalDeducted = document.getElementById('summary_total_deducted');

        function calculateConversion() {
            const selectedWallet = senderWalletSelect.options[senderWalletSelect.selectedIndex];
            const selectedCurrency = convertToCurrencySelect.options[convertToCurrencySelect.selectedIndex];
            const amount = parseFloat(amountInput.value) || 0;

            if (!selectedWallet || !selectedCurrency || amount <= 0) {
                conversionSummary.style.display = 'none';
                return;
            }

            const senderCurrency = selectedWallet.getAttribute('data-currency');
            const senderRate = parseFloat(selectedWallet.getAttribute('data-exchange-rate')) || 1;
            const targetRate = parseFloat(selectedCurrency.getAttribute('data-exchange-rate')) || 1;
            const targetCurrency = selectedCurrency.value;

            const converted = (amount / senderRate) * targetRate;
            const fee = amount * 0.05;
            const totalDeducted = amount + fee;

            convertedBalanceInput.value = `${converted.toFixed(2)} ${targetCurrency}`;
            conversionFeeInput.value = `${fee.toFixed(2)} ${senderCurrency}`;
            hiddenConvertedBalanceInput.value = converted.toFixed(2);
            hiddenConversionRateInput.value = targetRate.toFixed(6);
            hiddenCurrencyInput.value = targetCurrency;

            summaryExchangeRate.innerText = `1 ${senderCurrency} = ${(targetRate / senderRate).toFixed(6)} ${targetCurrency}`;
            summaryAmountToReceive.innerText = `${converted.toFixed(2)} ${targetCurrency}`;
            summaryConversionFee.innerText = `${fee.toFixed(2)} ${senderCurrency}`;
            summaryTotalDeducted.innerText = `${totalDeducted.toFixed(2)} ${senderCurrency}`;

            conversionSummary.style.display = 'block';
        }

        senderWalletSelect.addEventListener('change', calculateConversion);
        convertToCurrencySelect.addEventListener('change', calculateConversion);
        amountInput.addEventListener('input', calculateConversion);
        receiverInput.addEventListener('blur', calculateConversion);
    });
</script>
@endsection
