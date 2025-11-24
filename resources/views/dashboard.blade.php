<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Suppliers Card -->
                        <div class="bg-blue-50 dark:bg-blue-900 p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold text-blue-700 dark:text-blue-300 mb-2">Manage Suppliers
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Add, edit, or remove suppliers from your
                                inventory.</p>
                            <a href="{{ route('suppliers.index') }}"
                                class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Go to Suppliers
                            </a>
                        </div>

                        <!-- Products Card -->
                        <div class="bg-green-50 dark:bg-green-900 p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold text-green-700 dark:text-green-300 mb-2">Manage Products
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Manage your product catalog, prices, and
                                units.</p>
                            <a href="{{ route('products.index') }}"
                                class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Go to Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>