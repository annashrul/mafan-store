<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;

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

        // Kirim data ke view
        return view('dashboard', [
            'topProducts'=>$topProducts,
            'dates' => json_encode($dates),
            'counts' => json_encode($counts),
        ]);
    }
}
