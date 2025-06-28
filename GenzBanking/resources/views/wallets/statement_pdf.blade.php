<!DOCTYPE html>
<html>
<head>
    <title>Wallet Statement</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Wallet Statement for {{ $wallet->name }}</h2>
    <p>Balance: {{ $wallet->balance }} ({{ $wallet->currency }})</p>

    @if ($transactions->isNotEmpty())
        <table>
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
    @else
        <p>No transactions found for this wallet.</p>
    @endif
</body>
</html>