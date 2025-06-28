@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Currency</h2>
    <form action="{{ route('currencies.update', $currency->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="code" class="form-label">Currency Code</label>
            <input type="text" name="code" id="code" class="form-control" value="{{ $currency->code }}" disabled>
        </div>
        <div class="mb-3">
            <label for="exchange_rate" class="form-label">Exchange Rate</label>
            <input type="number" name="exchange_rate" id="exchange_rate" class="form-control" value="{{ $currency->exchange_rate }}" step="0.0001" required>
        </div>
        <button type="submit" class="btn btn-success">Update Currency</button>
    </form>
</div>
@endsection