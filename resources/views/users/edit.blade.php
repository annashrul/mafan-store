@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Edit User</h4>
        <hr/>

        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')



            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="level" class="form-label">Level</label>
                <select class="form-select @error('level') is-invalid @enderror" id="level" name="level" required>
                    <option value="">Select Level</option>
                    <option value="admin" {{ old('level', $user->level) == 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="kasir" {{ old('level', $user->level) == 'kasir' ? 'selected' : '' }}>Cashier</option>
                </select>
                @error('level')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password (Leave blank to keep current password)</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation">
                @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

<div class=" d-flex  justify-content-end gap-3">
                <button type="button" onclick="window.history.back()" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>        </form>
    </div>
@endsection
