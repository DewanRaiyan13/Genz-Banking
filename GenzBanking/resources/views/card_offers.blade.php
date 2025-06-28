@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <h2 class="display-4 text-gradient fw-bold">✨ Available Card Offers ✨</h2>
        <p class="lead text-muted">Explore our premium credit card options</p>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-glow">
                                <tr>
                                    <th class="py-3 px-4 bg-primary text-white text-center animate__animated animate__fadeInDown">Card Name</th>
                                    <th class="py-3 px-4 bg-primary text-white text-center animate__animated animate__fadeInDown animate__delay-1s">Description</th>
                                    <th class="py-3 px-4 bg-primary text-white text-center animate__animated animate__fadeInDown animate__delay-2s">Annual Fee</th>
                                    <th class="py-3 px-4 bg-primary text-white text-center animate__animated animate__fadeInDown animate__delay-3s">Interest Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($offers as $offer)
                                <tr class="offer-row animate__animated animate__fadeIn">
                                    <td class="py-3 px-4 align-middle text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="bi bi-credit-card-2-front-fill text-primary me-2"></i>
                                            <strong>{{ $offer->name }}</strong>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle">{{ $offer->description }}</td>
                                    <td class="py-3 px-4 align-middle text-center">
                                        @if($offer->annual_fee == 0)
                                            <span class="badge bg-success text-black p-2 animate__animated animate__pulse animate__infinite">$0 FREE</span>
                                        @else
                                            <span class="badge bg-warning text-black p-2">${{ $offer->annual_fee }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 align-middle text-center">
                                        <span class="rate-badge">{{ $offer->interest_rate }}%</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('card_requests.create') }}" class="btn btn-primary mt-3">Request for a Card</a>
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

    /* Rate Badge Styling */
    .rate-badge {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    /* Card Styling */
    .card {
        border-radius: 15px;
        overflow: hidden;
        border: none;
    }

    /* Animation Delays for Rows */
    .offer-row:nth-child(odd) {
        animation-delay: 0.1s;
    }
    .offer-row:nth-child(even) {
        animation-delay: 0.2s;
    }
</style>

<!-- Include Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<script>
    // Add hover class to rows
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.offer-row');
        rows.forEach(row => {
            row.addEventListener('mouseenter', () => {
                row.classList.add('animate__pulse');
            });
            row.addEventListener('mouseleave', () => {
                row.classList.remove('animate__pulse');
            });
        });
    });
</script>
@endsection