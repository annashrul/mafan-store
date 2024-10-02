<?php

namespace App\Http\Controllers;

use App\Models\MasterTransaction;
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
        $query = MasterTransaction::query();

        // Filter tanggal default (tanggal hari ini)
        $dateFrom = $request->has('date_from') ? $request->input('date_from') : now()->startOfDay()->format('Y-m-d');
        $dateTo = $request->has('date_to') ? $request->input('date_to') : now()->endOfDay()->format('Y-m-d');

        // Filter berdasarkan nama produk jika diberikan
        if ($request->has('product_name') && $request->input('product_name') !== '') {
            $query->whereHas('transactions.product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('product_name') . '%');
            });
        }

        // Filter berdasarkan rentang tanggal
        $query->whereBetween('created_at', [
            $dateFrom . ' 00:00:00',
            $dateTo . ' 23:59:59'
        ]);
      $transactions = MasterTransaction::with(['transactions.product', 'transactions.user'])
    ->get()
    ->map(function ($masterTransaction) {
        return [
            'transaction_no' => $masterTransaction->transaction_no,
            'total' => $masterTransaction->total,
            'created_at' => $masterTransaction->created_at,
            'detail' => $masterTransaction->transactions->map(function ($transaction) {
                return [
                    'product_name' => $transaction->product->name,
                    'product_price' => $transaction->product->price,
                    'qty' => $transaction->qty,
                    'user_name' => $transaction->user->name,
                    'total' => $transaction->total
                ];
            })
        ];
    });
       
       

        return view('transactions.index', [
            'transactions' => $transactions,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'totalQty'=>0,
            'totalAmount'=>0,
        ]);
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
            // Generate a unique transaction number, you can customize this to fit your needs
            $transactionNo = 'TRANS-' . strtoupper(uniqid());

            // Calculate the total for the master transaction
            $masterTotal = 0;

            // Create the master transaction
            $masterTransaction = MasterTransaction::create([
                'transaction_no' => $transactionNo,
                'total' => 0,  // Temporary, we'll update it after calculating the total from individual transactions
            ]);


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
                $masterTotal += $total;

                Transaction::create([
                    'master_transaction_id'=>$transactionNo,
                    'product_id' => $product->id,
                    'user_id' => $userId,
                    'qty' => $qty,
                    'total' => $total,
                ]);

                // Update stock
                $product->stock -= $qty;
                $product->save();
            }


            $masterTransaction->total = $masterTotal;
            $masterTransaction->save();
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