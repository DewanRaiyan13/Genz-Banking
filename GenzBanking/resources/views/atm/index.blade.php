@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Nearby ATMs</h4>
    </div>

    <div class="atm-list">
        @foreach($atms as $atm)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $atm['name'] }}</h5>
                <p class="card-text text-muted mb-1">{{ $atm['address'] }}</p>
                <p class="card-text"><small>Distance: ~{{ rand(200, 800) }}m</small></p>
                
                <a href="{{ route('withdraw', ['atm_id' => $atm['id']]) }}" 
                   class="btn btn-sm btn-success mt-2">
                    <i class="bi bi-cash-coin"></i> Cash Out
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

@section('scripts')
<script>
    // Any necessary JavaScript can go here
</script>
@endsection

<style>
    .atm-list {
        max-width: 600px;
        margin: 0 auto;
    }
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }
</style>
@endsection