<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'owner' || auth()->user()->role === 'warehouse_admin') {
        return redirect('/admin');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Warehouse Admin Routes (Moved to Filament)
    // Route::middleware(['role:warehouse_admin'])->group(function () {
    //     Route::resource('suppliers', SupplierController::class);
    //     Route::resource('products', ProductController::class);
    //     Route::get('/transactions/inbound', [TransactionController::class, 'createInbound'])->name('transactions.inbound.create');
    //     Route::post('/transactions/inbound', [TransactionController::class, 'storeInbound'])->name('transactions.inbound.store');
    // });

    // Owner Routes
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/owner/dashboard', function () {
            return redirect('/admin');
        })->name('owner.dashboard');
    });

    // Cashier Routes (Moved to Filament)
    // Route::middleware(['role:cashier'])->group(function () {
    //     Route::get('/pos', [TransactionController::class, 'indexPos'])->name('pos.index');
    //     Route::post('/transactions/outbound', [TransactionController::class, 'storeOutbound'])->name('transactions.outbound.store');
    // });

    Route::get('/pos', function () {
        return redirect('/admin/pos-page');
    });
});

require __DIR__ . '/auth.php';
