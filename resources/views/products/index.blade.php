@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between">
            <h4>Products</h4>
            <!-- Pencarian -->
            <form action="{{ route('products.index') }}" method="GET" style="width:70%">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="product_name" placeholder="Search by product name" value="{{ request('product_name') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                        <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="fas fa-add"></i> New Product</a>
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
                <th>#</th>
                <th>Name</th>
                <th>Purchase Price</th>
                <th>Selling Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td class="w1">c</td>
                    <td>{{ $product->name }}</td>
                    <td class="w1 text-right">{{ number_format($product->purchase_price) }}</td>
                    <td class="w1 text-right">{{ number_format($product->price) }}</td>
                    <td class="w1 text-right">{{ number_format($product->stock) }}</td>
                    <td class="w1">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i>  Edit</a>
                        <form  onsubmit="return confirmDelete();" action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No products found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <!-- Pagination Controls -->
        <div class="mt-3 d-flex justify-content-end">
            {{ $products->appends(request()->query())->links('pagination.bootstrap-5') }}
        </div>
    </div>
@endsection
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this product?');
    }
</script>
