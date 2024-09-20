<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{


    public function index(Request $request)
    {
        $query = Transaction::query()->with('product', 'user');

        // Default tanggal hari ini
        $dateFrom = $request->has('date_from') ? $request->input('date_from') : now()->startOfDay()->format('Y-m-d');
        $dateTo = $request->has('date_to') ? $request->input('date_to') : now()->endOfDay()->format('Y-m-d');

        // Filter berdasarkan nama produk
        if ($request->has('product_name') && $request->input('product_name') !== '') {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('product_name') . '%');
            });
        }

        // Filter berdasarkan rentang tanggal
        $query->whereBetween('created_at', [
            $dateFrom . ' 00:00:00',
            $dateTo . ' 23:59:59'
        ]);
        // Debugging output to check the query
        Log::info('Query:', [$query->toSql(), $query->getBindings()]);
        // Pagination
        $transactions = $query->paginate(10); // 10 transaksi per halaman
     
       // Calculate total qty and total amount per page
        $totalQty = $transactions->sum('qty');
        $totalAmount = $transactions->sum('total');
        return view('transactions.index', compact('transactions', 'dateFrom', 'dateTo', 'totalQty', 'totalAmount'));
    }

    public function create()
    {
        $products = Product::all();
        return view('transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transactions.*.product_id' => 'required|exists:products,id',
            'transactions.*.qty' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        // Start a database transaction to ensure all operations are done atomically
        DB::beginTransaction();

        try {
            foreach ($request->input('transactions') as $transactionData) {
                $product = Product::findOrFail($transactionData['product_id']);
                $qty = $transactionData['qty'];

                // Validate stock
                if ($product->stock < $qty) {
                    // Rollback the transaction and redirect with an error message if insufficient stock
                    DB::rollback();
                    return redirect()->back()->withErrors(['qty' => 'Insufficient stock available for product: ' . $product->name]);
                }

                // Create transaction
                $total = $product->price * $qty;
                Transaction::create([
                    'product_id' => $product->id,
                    'user_id' => $userId,
                    'qty' => $qty,
                    'total' => $total,
                ]);

                // Update stock
                $product->stock -= $qty;
                $product->save();
            }

            // Commit the transaction if all operations are successful
            DB::commit();

            return redirect()->route('transactions.index')
                ->with('success', 'Transactions completed successfully.');

        } catch (\Exception $e) {
            // Rollback the transaction and redirect with an error message if something goes wrong
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Failed to complete transactions. Please try again.']);
        }
    }
}