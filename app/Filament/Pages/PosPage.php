<?php

namespace App\Filament\Pages;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class PosPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'POS (Kasir)';
    protected static ?string $title = 'Point of Sale';

    protected static string $view = 'filament.pages.pos-page';

    public $search = '';
    public $cart = [];
    public $total = 0;
    public $change = 0;
    public $paymentAmount = 0;

    public function mount()
    {
        if (auth()->user()->role !== 'cashier' && auth()->user()->role !== 'owner') {
            // Optional: restrict access if needed, though canAccessPanel handles most
        }
    }

    public function getProductsProperty()
    {
        if (empty($this->search)) {
            return Product::limit(12)->get();
        }

        return Product::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->limit(12)
            ->get();
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product)
            return;

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->selling_price_pcs,
                'qty' => 1,
                'unit' => 'pcs', // Default to pcs for simplicity in this version
            ];
        }
        $this->calculateTotal();
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->calculateTotal();
    }

    public function updateQty($productId, $qty)
    {
        if ($qty < 1) {
            $this->removeFromCart($productId);
            return;
        }
        $this->cart[$productId]['qty'] = $qty;
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['qty'];
        });
        $this->calculateChange();
    }

    public function calculateChange()
    {
        if ($this->paymentAmount >= $this->total) {
            $this->change = $this->paymentAmount - $this->total;
        } else {
            $this->change = 0;
        }
    }

    public function updatedPaymentAmount()
    {
        $this->calculateChange();
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            Notification::make()->title('Cart is empty')->warning()->send();
            return;
        }

        if ($this->paymentAmount < $this->total) {
            Notification::make()->title('Insufficient payment')->danger()->send();
            return;
        }

        try {
            DB::transaction(function () {
                // 1. Create Transaction Header
                $transaction = Transaction::create([
                    'user_id' => auth()->id(),
                    'type' => 'outbound',
                    'total_amount' => $this->total,
                    'transaction_date' => now(),
                ]);

                foreach ($this->cart as $item) {
                    $product = Product::find($item['id']);
                    $requestedQty = $item['qty'];

                    // FEFO Logic
                    $batches = ProductBatch::where('product_id', $product->id)
                        ->where('current_qty_pcs', '>', 0)
                        ->orderBy('expired_date', 'asc')
                        ->lockForUpdate()
                        ->get();

                    $remainingQtyToDeduct = $requestedQty;

                    foreach ($batches as $batch) {
                        if ($remainingQtyToDeduct <= 0)
                            break;

                        $deductQty = min($batch->current_qty_pcs, $remainingQtyToDeduct);

                        $batch->current_qty_pcs -= $deductQty;
                        $batch->save();

                        TransactionDetail::create([
                            'transaction_id' => $transaction->id,
                            'product_batch_id' => $batch->id,
                            'qty' => $deductQty,
                            'price_at_moment' => $item['price'],
                        ]);

                        $remainingQtyToDeduct -= $deductQty;
                    }

                    if ($remainingQtyToDeduct > 0) {
                        throw new \Exception("Insufficient stock for {$product->name}");
                    }
                }
            });

            Notification::make()->title('Transaction successful')->success()->send();
            $this->reset(['cart', 'total', 'paymentAmount', 'change']);

        } catch (\Exception $e) {
            Notification::make()->title('Error: ' . $e->getMessage())->danger()->send();
        }
    }
}
