<!-- filepath: d:\Banking Website\GenzBanking\resources\views\passport_endorsements.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <h2 class="display-4 text-gradient fw-bold">🌍 Passport Endorsement Requests 🌍</h2>
        <p class="lead text-muted">Review all submitted passport endorsement requests</p>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-glow">
                                <tr>
                                    <th class="py-3 px-4 bg-primary text-white text-center animate__animated animate__fadeInDown">User</th>
                                    <th class="py-3 px-4 bg-primary text-white text-center animate__animated animate__fadeInDown animate__delay-1s">Passport Number</th>
                                    <th class="py-3 px-4 bg-primary text-white text-center animate__animated animate__fadeInDown animate__delay-2s">Wallet</th>
                                    <th class="py-3 px-4 bg-primary text-white text-center animate__animated animate__fadeInDown animate__delay-3s">Amount (USD)</th>
                                    <th class="py-3 px-4 bg-primary text-white text-center animate__animated animate__fadeInDown animate__delay-4s">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($endorsements as $endorsement)
                                <tr class="offer-row animate__animated animate__fadeIn">
                                    <td class="py-3 px-4 align-middle text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="bi bi-person-circle text-primary me-2"></i>
                                            <strong>{{ $endorsement->user->name }}</strong>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle text-center">{{ $endorsement->passport_number }}</td>
                                    <td class="py-3 px-4 align-middle text-center">{{ $endorsement->wallet->name }}</td>
                                    <td class="py-3 px-4 align-middle text-center">${{ number_format($endorsement->amount_usd, 2) }}</td>
                                    <td class="py-3 px-4 align-middle text-center">
                                        @if($endorsement->status == 'pending')
                                            <span class="badge bg-warning text-black p-2">Pending</span>
                                        @elseif($endorsement->status == 'approved')
                                            <span class="badge bg-success text-black p-2">Approved</span>
                                        @else
                                            <span class="badge bg-danger text-black p-2">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Gradient Text */
    .text-gradient {
        background: linear-gradient(45deg, #6a11cb 0%, #2575fc 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    /* Table Header Glow Effect */
    .thead-glow th {
        position: relative;
    }
    
    .thead-glow th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
    }

    /* Offer Row Hover Effect */
    .offer-row {
        transition: all 0.3s ease;
    }
    
    .offer-row:hover {
        transform: translateY(-2px);
        background-color: rgba(106, 17, 203, 0.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    /* Badge Styling */
    .badge {
        font-weight: 600;
        border-radius: 20px;
    }
</style>

<!-- Include Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection