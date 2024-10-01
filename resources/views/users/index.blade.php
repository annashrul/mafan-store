@extends('layouts.app')

@section('content')
    <div class="container">
         <div class="d-flex justify-content-between">
            <h4>Users</h4>
            <!-- Pencarian -->
            <form action="{{ route('users.index') }}" method="GET" style="width:70%">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="search" placeholder="Search by name, email, or level" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                        <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fas fa-add"></i> New User</a>

                    </div>
                </div>
            </form>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <hr/>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Level</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->level }}</td>

                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                        <form onsubmit="return confirmDelete();" action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No users found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-3 d-flex justify-content-end">
            {{ $users->appends(request()->query())->links('pagination.bootstrap-5') }}
        </div>
    </div>
@endsection
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this user?');
    }
</script>
