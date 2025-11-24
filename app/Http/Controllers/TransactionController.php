<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // Show Inbound Form
    public function createInbound()
    {
        $suppliers = \App\Models\Supplier::all();
        $products = \App\Models\Product::all();
        return view('admin.transactions.inbound', compact('suppliers', 'products'));
    }

    // Inbound Transaction (Purchase / Stock In)
    public function storeInbound(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id',
            'batch_code' => 'required|string',
            'expired_date' => 'required|date',
            'qty' => 'required|integer|min:1',
            'unit_type' => 'required|in:small,large', // Input unit
            'purchase_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $product = Product::findOrFail($request->product_id);

            // Convert to Pcs
            $qtyPcs = $request->qty;
            if ($request->unit_type === 'large') {
                $qtyPcs = $request->qty * $product->conversion_rate;
            }

            // Calculate Price Per Pcs
            $pricePerPcs = $request->purchase_price;
            if ($request->unit_type === 'large') {
                $pricePerPcs = $request->purchase_price / $product->conversion_rate;
            }

            // 1. Create Product Batch
            $batch = ProductBatch::create([
                'product_id' => $product->id,
                'supplier_id' => $request->supplier_id,
                'batch_code' => $request->batch_code,
                'expired_date' => $request->expired_date,
                'current_qty_pcs' => $qtyPcs,
                'purchase_price_per_pcs' => $pricePerPcs,
            ]);

            // 2. Create Transaction Header
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'type' => 'inbound',
                'total_amount' => $qtyPcs * $pricePerPcs,
                'transaction_date' => now(),
            ]);

            // 3. Create Transaction Detail
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_batch_id' => $batch->id,
                'qty' => $qtyPcs,
                'price_at_moment' => $pricePerPcs,
            ]);
        });

        return redirect()->back()->with('success', 'Stock added successfully.');
    }

    // Outbound Transaction (Sales / Stock Out) - FEFO Logic
    public function storeOutbound(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'unit_type' => 'required|in:small,large',
        ]);

        DB::transaction(function () use ($request) {
            $product = Product::findOrFail($request->product_id);

            // Convert requested qty to Pcs
            $requestedQtyPcs = $request->qty;
            if ($request->unit_type === 'large') {
                $requestedQtyPcs = $request->qty * $product->conversion_rate;
            }

            // FEFO Algorithm
            // 1. Find batches with stock, sorted by expired_date ASC
            $batches = ProductBatch::where('product_id', $product->id)
                ->where('current_qty_pcs', '>', 0)
                ->orderBy('expired_date', 'asc')
                ->lockForUpdate() // Prevent race conditions
                ->get();

            $totalStock = $batches->sum('current_qty_pcs');

            if ($totalStock < $requestedQtyPcs) {
                throw new \Exception("Insufficient stock. Available: $totalStock Pcs, Requested: $requestedQtyPcs Pcs");
            }

            // 2. Create Transaction Header
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'type' => 'outbound',
                'total_amount' => 0, // Will update later
                'transaction_date' => now(),
            ]);

            $remainingQtyToDeduct = $requestedQtyPcs;
            $totalTransactionAmount = 0;

            foreach ($batches as $batch) {
                if ($remainingQtyToDeduct <= 0)
                    break;

                $deductQty = min($batch->current_qty_pcs, $remainingQtyToDeduct);

                // Deduct from batch
                $batch->current_qty_pcs -= $deductQty;
                $batch->save();

                // Calculate price (Selling Price)
                // Assuming selling price is constant from Product Master, not Batch
                $sellingPrice = $product->selling_price_pcs;
                $subtotal = $deductQty * $sellingPrice;
                $totalTransactionAmount += $subtotal;

                // Create Detail
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_batch_id' => $batch->id,
                    'qty' => $deductQty,
                    'price_at_moment' => $sellingPrice,
                ]);

                $remainingQtyToDeduct -= $deductQty;
            }

            // Update Total Amount
            $transaction->update(['total_amount' => $totalTransactionAmount]);
        });

        return redirect()->back()->with('success', 'Transaction successful.');
    }
    // Show POS Interface
    public function indexPos()
    {
        $products = \App\Models\Product::all();
        return view('cashier.pos', compact('products'));
    }
}
