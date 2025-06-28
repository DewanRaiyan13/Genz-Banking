@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Wallet Statement</h2>

    @if ($transactions->isEmpty())
        <p>No transactions found for this wallet.</p>
    @else
        <p>Transactions found: {{ $transactions->count() }}</p>
        <a href="{{ route('wallets.statement.download', $wallet->id) }}" class="btn btn-primary mb-3">
            Download PDF
        </a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at }}</td>
                        <td>
                            @if (isset($transaction->sender_wallet_id) && $transaction->sender_wallet_id == $wallet->id)
                                Debit
                            @elseif (isset($transaction->receiver_wallet_id) && $transaction->receiver_wallet_id == $wallet->id)
                                Credit
                            @else
                                Multi-Currency Transfer
                            @endif
                        </td>
                        <td>{{ $transaction->amount }}</td>
                        <td>
                            @if (isset($transaction->sender_currency))
                                {{ $transaction->sender_currency }} → {{ $transaction->receiver_currency }}
                            @else
                                {{ $wallet->currency }}
                            @endif
                        </td>
                        <td>{{ $transaction->description ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection