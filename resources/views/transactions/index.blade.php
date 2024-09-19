@extends('layouts.app')

<style>

td{
	/* font-weight: 00 !important */
}
td,th {
	white-space: nowrap !important;
	vertical-align: middle !important;
}
.w1{
	width: 1% !important;
}
.text-right{
	text-align: right !important;
}

thead{
	background-color: #EEEEEE !important;
}
tfoot{
	background-color: gray !important;
	color: #EEEEEE !important
}


    
</style>

@section('content')
<div class="container">
	<div class="d-flex justify-content-between align-items-center">
    <h4 style="width: 20%">Transactions</h4>
    <form action="{{ route('transactions.index') }}" method="GET" class="d-flex">

        <div class="row d-flex">
            <div class="col-md-3">
                <input type="text" class="form-control" id="product_name" name="product_name"
                    value="{{ request('product_name') }}" placeholder="Search by product name">
            </div>

            <div class="col-md-2" style="padding: 0">
                <input type="text" class="form-control" id="date_from" name="date_from"
                    value="{{ old('date_from', $dateFrom) }}" placeholder="Date from">
            </div>
			<div class="col-md-1" style="margin-top: 10px;text-align: center;padding:0">
                <i class="fas fa-exchange-alt"></i>
            </div>

            <div class="col-md-2" style="padding: 0">
                <input type="text" class="form-control" id="date_to" name="date_to"
                    value="{{ old('date_to', $dateTo) }}" placeholder="Date to">
            </div>
            
            <div class="col-md-4 d-flex align-items-center">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                    <i class="fas fa-add"></i> Transaction
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


	<table class="table table-responsive table-hover">
		<thead>
			<tr>
				<th>#</th> <!-- Column for numbering -->
				<th>Product</th>
				<th>Qty</th>
				<th>Total</th>
				<th>User</th>

				<th>Date</th>
			</tr>
		</thead>
		<tbody>
			@forelse($transactions as $transaction)
				
			<tr>
				<td class="w1">{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
				<td>{{ $transaction->product->name }}</td>
				<td class="w1 text-right">{{ $transaction->qty }}</td>
				<td class="w1 text-right">{{ number_format($transaction->total) }}</td>
				<td class="w1">{{ $transaction->user->name }}</td>
				
				<td class="w1">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
			</tr>
			@empty
			<tr>
				<td colspan="6" class="text-center">No transactions found.</td>
			</tr>
			@endforelse
		</tbody>
		<tfoot>
			<tr>
				<th colspan="2">TOTAL PERPAGE</th>
				<th class="text-right" colspan="1">{{ number_format($totalQty) }}</th>
				<th class="text-right" colspan="1">{{ number_format($totalAmount) }}</th>
				<th colspan="2"></th>
			</tr>
		</tfoot>
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