<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Receipt</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #fff);
            margin: 0;
            padding: 40px 20px;
        }

        .receipt-container {
            max-width: 650px;
            margin: auto;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
            border-top: 6px solid #007bff;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            color: #007bff;
            font-size: 28px;
        }

        .header p {
            font-size: 14px;
            color: #6c757d;
            margin-top: 5px;
        }

        .details {
            border-top: 1px solid #dee2e6;
            padding-top: 25px;
            font-size: 16px;
        }

        .details p {
            margin: 10px 0;
        }

        .details span.label {
            font-weight: 600;
            color: #343a40;
            display: inline-block;
            width: 120px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #555;
        }

        .footer strong {
            color: #007bff;
        }

        .amount-highlight {
            background-color: #f1f9ff;
            border-left: 4px solid #007bff;
            padding: 10px 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 18px;
            color: #007bff;
            font-weight: bold;
        }

        .print-btn {
            margin-top: 25px;
            display: flex;
            justify-content: center;
        }

        .print-btn button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .print-btn button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Transaction Receipt</h1>
        <p>Transaction ID: {{ $transaction->id }}</p>
    </div>
    <div class="details">
        <p><strong>Date:</strong> {{ $date }}</p>
        <p><strong>Amount:</strong> ${{ number_format($transaction->amount, 2) }}</p>
        <p><strong>Sender:</strong> {{ $transaction->senderWallet->user->name ?? 'N/A' }}</p>
        <p><strong>Receiver:</strong> {{ $transaction->receiverWallet->user->name ?? 'N/A' }}</p>
        <p><strong>Description:</strong> {{ $transaction->description ?? 'N/A' }}</p>
    </div>
    <div class="footer">
        <p>Thank you for using DewanBanking!</p>
    </div>
</body>
</html>
