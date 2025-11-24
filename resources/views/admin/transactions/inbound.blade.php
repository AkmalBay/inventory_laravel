<x-app-layout>
    <x-slot name="header">
        {{ __('Stock In (Inbound)') }}
    </x-slot>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form action="{{ route('transactions.inbound.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Supplier</span>
                    <select name="supplier_id"
                        class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                        required>
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Product</span>
                    <select name="product_id"
                        class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                        required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->code }})</option>
                        @endforeach
                    </select>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Batch Code</span>
                    <input type="text" name="batch_code"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        placeholder="Batch Code" required />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Expired Date</span>
                    <input type="date" name="expired_date"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        required />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Quantity</span>
                    <input type="number" name="qty"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        min="1" required />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Unit Type</span>
                    <select name="unit_type"
                        class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                        required>
                        <option value="small">Small Unit (e.g. Pcs)</option>
                        <option value="large">Large Unit (e.g. Box)</option>
                    </select>
                </label>

                <label class="block text-sm sm:col-span-2">
                    <span class="text-gray-700 dark:text-gray-400">Purchase Price (Total for Qty)</span>
                    <input type="number" name="purchase_price"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        min="0" step="0.01" required />
                    <span class="text-xs text-gray-600 dark:text-gray-400">Enter the price for the specific unit
                        selected above.</span>
                </label>
            </div>

            <div class="flex mt-6 justify-end">
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Submit Inbound Transaction
                </button>
            </div>
        </form>
    </div>
</x-app-layout>