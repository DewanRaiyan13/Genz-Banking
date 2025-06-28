@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Currency Exchange Rates</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('currencies.create') }}" class="btn btn-primary mb-3">Add New Currency</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Exchange Rate</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($currencies as $currency)
                <tr>
                    <td>{{ $currency->code }}</td>
                    <td>{{ $currency->name }}</td>
                    <td>{{ $currency->rate }}</td>
                    <td>
                        <a href="{{ route('currencies.edit', $currency->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('currencies.destroy', $currency->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection