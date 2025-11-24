<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Product Grid -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Search Bar -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products by name or code..."
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($this->products as $product)
                    <div wire:click="addToCart({{ $product->id }})"
                        class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 cursor-pointer hover:shadow-md transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 group">
                        <div class="h-24 bg-gray-100 rounded-lg mb-3 flex items-center justify-center dark:bg-gray-700">
                            <span class="text-3xl">📦</span>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-white truncate group-hover:text-amber-600 transition-colors">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $product->code }}</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-amber-600 dark:text-amber-400">Rp {{ number_format($product->selling_price_pcs, 0, ',', '.') }}</span>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded dark:bg-gray-700 dark:text-gray-300">Stock: {{ $product->stock }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
                        No products found.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Cart Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-4 dark:bg-gray-800 dark:border-gray-700 flex flex-col h-[calc(100vh-8rem)]">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <span>🛒</span> Current Order
                    </h2>
                </div>

                <!-- Cart Items -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4">
                    @forelse($cart as $productId => $item)
                        <div class="flex items-center justify-between gap-3 p-3 bg-gray-50 rounded-lg dark:bg-gray-700/50">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800 dark:text-white truncate">{{ $item['name'] }}</h4>
                                <p class="text-sm text-amber-600 dark:text-amber-400">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button wire:click="updateQty({{ $productId }}, {{ $item['qty'] - 1 }})" 
                                    class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300 flex items-center justify-center dark:bg-gray-600 dark:text-gray-300">-</button>
                                <span class="w-8 text-center font-medium dark:text-white">{{ $item['qty'] }}</span>
                                <button wire:click="updateQty({{ $productId }}, {{ $item['qty'] + 1 }})"
                                    class="w-6 h-6 rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 flex items-center justify-center dark:bg-amber-900/50 dark:text-amber-400">+</button>
                            </div>
                            <button wire:click="removeFromCart({{ $productId }})" class="text-red-400 hover:text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">
                            <p>Cart is empty</p>
                            <p class="text-sm">Click products to add</p>
                        </div>
                    @endforelse
                </div>

                <!-- Footer -->
                <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-xl dark:bg-gray-700/50 dark:border-gray-700">
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-lg font-bold text-gray-800 dark:text-white">
                            <span>Total</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Amount</label>
                            <input type="number" wire:model.live.debounce.500ms="paymentAmount" 
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        
                        @if($paymentAmount > 0)
                            <div class="flex justify-between text-sm font-medium {{ $change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                <span>Change</span>
                                <span>Rp {{ number_format($change, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <button wire:click="checkout" 
                            class="w-full py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-lg shadow-lg shadow-amber-600/20 transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            @if(empty($cart) || $paymentAmount < $total) disabled @endif>
                            Complete Transaction
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
