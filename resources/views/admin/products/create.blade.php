<x-app-layout>
    <x-slot name="header">
        {{ __('Add Product') }}
    </x-slot>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Product Code</span>
                    <input type="text" name="code"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        placeholder="e.g., P001" required />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Product Name</span>
                    <input type="text" name="name"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        placeholder="Product Name" required />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Unit Type (Small)</span>
                    <input type="text" name="unit_type_small"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        placeholder="e.g., Pcs" required />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Unit Type (Large)</span>
                    <input type="text" name="unit_type_large"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        placeholder="e.g., Box" required />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Conversion Rate (1 Large = ? Small)</span>
                    <input type="number" name="conversion_rate"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        min="1" required />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Stock Alert (Min Pcs)</span>
                    <input type="number" name="stock_alert"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        min="0" required />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Selling Price (Per Small Unit)</span>
                    <input type="number" name="selling_price_pcs"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        min="0" step="0.01" required />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Selling Price (Per Large Unit)</span>
                    <input type="number" name="selling_price_box"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        min="0" step="0.01" required />
                </label>
            </div>

            <div class="flex mt-6 justify-end">
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Save Product
                </button>
            </div>
        </form>
    </div>
</x-app-layout>