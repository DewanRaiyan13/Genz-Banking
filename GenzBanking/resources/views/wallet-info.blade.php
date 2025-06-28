<div class="card mb-4">
    <div class="card-header">
        <h5>Wallet Information</h5>
    </div>
    <div class="card-body">
        <p><strong>Wallet Holder:</strong> {{ auth()->user()->name }}</p>

        @php
            $wallet = auth()->user()->wallet;

            $currencySymbols = [
                'USD' => '$',
                'EUR' => '€',
                'GBP' => '£',
                'BDT' => '৳',
                'INR' => '₹',
                'JPY' => '¥',
                'CAD' => 'C$',
                'AUD' => 'A$',
                'CNY' => '¥',
                'KRW' => '₩',
                // Add more if needed
            ];
        @endphp

        @if($wallet)
            @php
                $symbol = $currencySymbols[$wallet->currency] ?? $wallet->currency;
                $formattedBalance = number_format($wallet->balance, 2);
            @endphp
            <p><strong>Wallet ID:</strong> {{ $wallet->id }}</p>
            <p><strong>Balance:</strong> {{ $symbol }}{{ $formattedBalance }} ({{ $wallet->currency }})</p>
        @else
            <p><strong>No wallet found. Please create one.</strong></p>
        @endif
    </div>
</div>
