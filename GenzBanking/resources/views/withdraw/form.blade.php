@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">ATM Withdrawal</h5>
                </div>
                
                <div class="card-body">
                    @if($atm)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        You're withdrawing from: <strong>{{ $atm->name }}</strong>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('withdraw.submit') }}">
                        @csrf
                        <input type="hidden" name="atm_id" value="{{ $atm->id ?? '' }}">

                        <div class="mb-3">
                            <label class="form-label">Amount (BDT)</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" 
                                       class="form-control" 
                                       name="amount" 
                                       placeholder="Enter amount" 
                                       min="100" 
                                       max="20000" 
                                       required>
                            </div>
                            <small class="text-muted">Min: 100 BDT, Max: 20,000 BDT</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Current Balance</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="BDT {{ number_format($balance, 2) }}" 
                                   readonly>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-cash-coin"></i> Confirm Withdrawal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection