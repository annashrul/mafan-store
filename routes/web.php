<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
Route::get('/', function () {
    return redirect()->route('login.form');
});
// Rute untuk menampilkan formulir login
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login.form');

// Middleware untuk autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('transactions', TransactionController::class);
});

// Rute untuk proses login
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Rute untuk logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login.form');
})->name('logout');
