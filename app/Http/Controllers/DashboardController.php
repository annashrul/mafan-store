<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data transaksi dari database
        $transactions = Transaction::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get();

        // Parsing data untuk chart
        $dates = [];
        $counts = [];

        foreach ($transactions as $transaction) {
            $dates[] = $transaction->date;
            $counts[] = $transaction->count;
        }


        // Ambil 10 barang terlaris berdasarkan jumlah transaksi
        $topProducts = Transaction::selectRaw('product_id, SUM(qty) as total_qty')
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->get()
            ->map(function($transaction) {
                $product = Product::find($transaction->product_id);
                return [
                    'name' => $product->name,
                    'total_qty' => $transaction->total_qty,
                ];
            });


             // Ambil data transaksi berdasarkan user yang login
            $user = User::find(Auth::id());



            $transactionsByUser = Transaction::select('users.name', DB::raw('count(*) as total_transactions'))
        ->join('users', 'transactions.user_id', '=', 'users.id')
        // ->where('transactions.user_id', $userId)
        ->groupBy('users.name')
        ->get();

            // dd($user->name);

        // Kirim data ke view
        return view('dashboard', [
            'user' => $user, // tambahkan data user ke array data
            'transactionsByUser'=>$transactionsByUser,
            'topProducts'=>$topProducts,
            'dates' => json_encode($dates),
            'counts' => json_encode($counts),
        ]);
    }
}