@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Products</h1>
        <!-- Pencarian -->
        <form action="{{ route('products.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" class="form-control" name="product_name" placeholder="Search by product name" value="{{ request('product_name') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('products.create') }}" class="btn btn-primary">New Product</a>
                </div>
            </div>
        </form>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
        <div class="mt-3" style="float: right">
            {{ $products->appends(request()->query())->links('pagination.bootstrap-5') }}
        </div>
    </div>
@endsection
