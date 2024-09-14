@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Transactions</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <div id="transaction-rows">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="product_id_1" class="form-label">Product</label>
                        <select class="form-select" id="product_id_1" name="transactions[0][product_id]" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }} (Rp. {{ number_format($product->price) }} - Stock: {{$product->stock}})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="qty_1" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="qty_1" name="transactions[0][qty]" min="1" required>
                    </div>

                    <div class="col-md-4">
                        <button type="button" class="btn btn-danger remove-row" style="margin-top: 33px">Remove</button>
                    </div>
                </div>
            </div>

            <h3>Total Amount: <span id="total-amount">0</span></h3>

            <button type="button" class="btn btn-primary" id="add-row">Add Another Row</button>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let rowIndex = 1;

            // Function to calculate total
            function calculateTotal() {
                let total = 0;

                document.querySelectorAll('select[name^="transactions"]').forEach(function (select) {
                    const productId = select.value;
                    const qtyInput = select.closest('.row').querySelector('input[name^="transactions"][type="number"]');
                    const qty = qtyInput ? parseInt(qtyInput.value, 10) : 0;

                    if (productId && qty > 0) {
                        const price = parseFloat(select.querySelector('option[value="' + productId + '"]').dataset.price);
                        total += price * qty;
                    }
                });

                document.getElementById('total-amount').textContent = total.toFixed(2);
            }

            // Add new row functionality
            document.getElementById('add-row').addEventListener('click', function () {
                const newRow = document.createElement('div');
                newRow.classList.add('row', 'mb-3');
                newRow.innerHTML = `
                    <div class="col-md-4">
                        <label for="product_id_${rowIndex}" class="form-label">Product</label>
                        <select class="form-select" id="product_id_${rowIndex}" name="transactions[${rowIndex}][product_id]" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }} (Rp. {{ number_format($product->price) }} - Stock: {{$product->stock}})</option>
                            @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="qty_${rowIndex}" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="qty_${rowIndex}" name="transactions[${rowIndex}][qty]" min="1" required>
                    </div>

                    <div class="col-md-4">
                        <button type="button" class="btn btn-danger mt-4 remove-row">Remove</button>
                    </div>
                `;

                document.getElementById('transaction-rows').appendChild(newRow);
                rowIndex++;
            });

            // Remove row functionality
            document.getElementById('transaction-rows').addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-row')) {
                    event.target.closest('.row').remove();
                    calculateTotal(); // Recalculate total after removing a row
                }
            });

            // Calculate total when product or quantity changes
            document.getElementById('transaction-rows').addEventListener('change', function (event) {
                if (event.target.matches('select, input[type="number"]')) {
                    calculateTotal();
                }
            });
        });
    </script>
@endsection
