@extends('layouts.app')

@section('content')
<div class="container">
	<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 style="width: 20%">Transactions</h4>
    <form action="{{ route('transactions.index') }}" method="GET" class="d-flex">
        <div class="row d-flex">
            <div class="col-md-4">
                <input type="text" class="form-control" id="product_name" name="product_name"
                    value="{{ request('product_name') }}" placeholder="Search by product name">
            </div>

            <div class="col-md-2">
                <input type="text" class="form-control" id="date_from" name="date_from"
                    value="{{ old('date_from', $dateFrom) }}" placeholder="Date from">
            </div>

            <div class="col-md-2">
                <input type="text" class="form-control" id="date_to" name="date_to"
                    value="{{ old('date_to', $dateTo) }}" placeholder="Date to">
            </div>
            
            <div class="col-md-4 d-flex align-items-center">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                    <i class="fas fa-add"></i> New Transaction
                </a>
            </div>
        </div>
    </form>
</div>




	@if(session('success'))
	<div class="alert alert-success">
		{{ session('success') }}
	</div>
	@endif

	<hr/>


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
				<td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
			</tr>
			@empty
			<tr>
				<td colspan="6" class="text-center">No transactions found.</td>
			</tr>
			@endforelse
		</tbody>
	</table>
	<!-- Pagination Controls -->
	<div class="mt-3 d-flex justify-content-end">

		{{ $transactions->appends(request()->query())->links('pagination.bootstrap-5') }}
	</div>
</div>

<script>
$(document).ready(function() {
	$('#date_from').datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	}).datepicker('setDate', '{{ old('
		date_from ', $dateFrom) }}');

	$('#date_to').datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	}).datepicker('setDate', '{{ old('
		date_to ', $dateTo) }}');
});
</script>
@endsection