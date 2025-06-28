@extends('layouts.app')

@section('content')
<!-- Animate.css + Bootstrap Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    body {
        background-color: #f4f6f9;
    }

    .card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        animation: fadeInUp 1s ease-in-out;
    }

    .card-header {
        background: linear-gradient(90deg, #4e73df, #224abe);
        color: #fff;
        font-size: 1.5rem;
        font-weight: 600;
        text-align: center;
        padding: 1rem;
    }

    .card-body {
        padding: 2rem;
        font-size: 1.1rem;
        color: #333;
    }

    .alert {
        background-color: #e7f5ff;
        color: #4e73df;
        border-left: 4px solid #4e73df;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }

    a {
        display: inline-block;
        margin-top: 1.5rem;
        font-weight: 500;
        color: #4e73df;
        font-size: 1.1rem;
        text-decoration: none;
        padding: 0.8rem 1.5rem;
        border: 2px solid #4e73df;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    a:hover {
        background: #4e73df;
        color: #fff;
        transform: translateY(-2px);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card animate__animated animate__fadeInUp">
                <div class="card-header">{{ __('GENZ WALLET') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <a href="{{ route('dashboard') }}">See the dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
