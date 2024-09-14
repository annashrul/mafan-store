@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Transactions</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('transactions.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" id="product_name" name="product_name" value="{{ request('product_name') }}" placeholder="search by product name">
                </div>

                <div class="col-md-3">
                    <input type="text" class="form-control" id="date_from" name="date_from" value="{{ old('date_from', $dateFrom) }}" placeholder="date from">
                </div>

                <div class="col-md-3">
                    <input type="text" class="form-control" id="date_to" name="date_to" value="{{ old('date_to', $dateTo) }}" placeholder="date to">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('transactions.create') }}" class="btn btn-primary">New Transaction</a>

                </div>
            </div>

        </form>


        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th> <!-- Column for numbering -->
                <th>Product</th>
                <th>User</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                    <td>{{ $transaction->product->name }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td>{{ $transaction->qty }}</td>
                    <td>{{ $transaction->total }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No transactions found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <!-- Pagination Controls -->
        <div class="mt-3" style="float: right">

            {{ $transactions->appends(request()->query())->links('pagination.bootstrap-5') }}
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#date_from').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            }).datepicker('setDate', '{{ old('date_from', $dateFrom) }}');

            $('#date_to').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            }).datepicker('setDate', '{{ old('date_to', $dateTo) }}');
        });
    </script>
@endsection
