<x-app-layout>
    <x-slot name="header">
        {{ __('Point of Sales (POS)') }}
    </x-slot>

    <div class="flex flex-col h-full gap-6 md:flex-row">
        <!-- Left Side: Product Grid -->
        <div class="flex-1 h-full min-h-0 overflow-y-auto">
            <!-- Search Bar -->
            <div class="relative w-full max-w-xl mr-6 focus-within:text-purple-500 mb-6">
                <div class="absolute inset-y-0 flex items-center pl-2">
                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input
                    class="w-full pl-8 pr-2 text-sm text-gray-700 placeholder-gray-600 bg-white border-0 rounded-md dark:placeholder-gray-500 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:placeholder-gray-500 focus:bg-white focus:border-purple-300 focus:outline-none focus:shadow-outline-purple form-input"
                    type="text" placeholder="Search products..." aria-label="Search" />
            </div>

            <!-- Grid -->
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-3">
                @foreach($products as $product)
                    <div
                        class="relative p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 group">
                        <div class="absolute top-0 right-0 p-2">
                            <span
                                class="px-2 py-1 text-xs font-bold leading-none text-white bg-purple-600 rounded-full">{{ $product->code }}</span>
                        </div>
                        <div class="mt-4">
                            <h4 class="mb-2 font-semibold text-gray-600 dark:text-gray-300">
                                {{ $product->name }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Stock: {{ $product->productBatches->sum('current_qty_pcs') }} Pcs
                            </p>
                            <p class="text-lg font-bold text-gray-700 dark:text-gray-200 mt-2">
                                Rp {{ number_format($product->selling_price_pcs, 0, ',', '.') }}
                            </p>
                        </div>
                        <!-- Add to Cart Overlay (Visual Only for now) -->
                        <div
                            class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-150 bg-black bg-opacity-10 rounded-lg">
                            <button
                                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right Side: Cart Summary -->
        <div class="w-full md:w-1/3 flex flex-col h-full bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="p-4 border-b dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Current Order</h2>
            </div>

            <div class="flex-1 p-4 overflow-y-auto">
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center h-full text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <p>Your cart is empty.</p>
                </div>
            </div>

            <div class="p-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-b-lg">
                <div class="flex justify-between mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    <span>Subtotal</span>
                    <span>Rp 0</span>
                </div>
                <div class="flex justify-between mb-4 text-xl font-bold text-gray-800 dark:text-gray-200">
                    <span>Total</span>
                    <span>Rp 0</span>
                </div>

                <!-- Manual Form for now as requested by previous logic -->
                <form action="{{ route('transactions.outbound.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <select name="product_id"
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                            required>
                            <option value="">-- Select Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2 mb-4">
                        <input type="number" name="qty"
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="Qty" required min="1" />
                        <select name="unit_type"
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                            required>
                            <option value="small">Pcs</option>
                            <option value="large">Box</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Checkout
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>