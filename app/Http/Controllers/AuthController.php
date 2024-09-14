<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Jika berhasil login, redirect ke halaman dashboard atau halaman lain
            return redirect()->intended('dashboard');
        }

        // Jika login gagal, kembali ke halaman login dengan error
        return back()->withErrors([
            'error' => 'email atau password salah.',
        ]);
    }
}
