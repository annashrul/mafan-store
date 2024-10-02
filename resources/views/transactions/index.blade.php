@extends('layouts.app')

<style>


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

                <div class="col-md-3" style="padding: 0">
                    <input type="text" class="form-control" id="date_from" name="date_from"
                        value="{{ old('date_from', $dateFrom) }}" placeholder="Date from">
                </div>
                <div class="col-md-1" style="margin-top: 10px;text-align: center;padding:0">
                    <i class="fas fa-exchange-alt"></i>
                </div>

                <div class="col-md-3" style="padding: 0">
                    <input type="text" class="form-control" id="date_to" name="date_to"
                        value="{{ old('date_to', $dateTo) }}" placeholder="Date to">
                </div>

                <div class="col-md-2 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Search
                    </button>

                </div>
            </div>
        </form>
    </div>




    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <hr />


    <table class="table table-responsive table-hover">
        <thead>
            <tr>
                <th>Transaction No</th>
                <th>Cashier</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
            <tr>
                <td>{{ $transaction['transaction_no'] }}</td>
                <td>{{ $transaction['created_at'] }}</td>

            </tr>


            @foreach($transaction['detail'] as $detail)
            <tr>
                <td colspan="1">
                    - {{ $detail['product_name'] }}
                </td>
                <td colspan="2">
                    <span style="text-align: right">x{{ $detail['qty'] }}</span> ({{ number_format($detail['total']) }})

                </td>


            </tr>

            @endforeach
            @empty
            <tr>
                <td colspan="6" class="text-center">No transactions found.</td>
            </tr>
            @endforelse
        </tbody>

        <div class="mt-3 d-flex justify-content-end">

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