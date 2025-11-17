<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RestockOrderController;
use App\Http\Controllers\SupplierOrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // rute untuk admin dan manager
    Route::middleware(['role:admin,manager'])->group(function () {
        // Fitur "Manajemen Kategori"
        Route::resource('categories', CategoryController::class);
        // Fitur "Manajemen Produk"
        Route::resource('products', ProductController::class);
    });

    // rute untuk staff dan manager
    Route::middleware(['role:staff,manager'])->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        
        // transaksi barang masuk dan keluar
        // Rute untuk Barang Masuk
        Route::get('/transactions/incoming/create', [TransactionController::class, 'createIncoming'])->name('transactions.create.incoming');
        Route::post('/transactions/incoming', [TransactionController::class, 'storeIncoming'])->name('transactions.store.incoming');
        // Rute untuk Barang Keluar
        Route::get('/transactions/outgoing/create', [TransactionController::class, 'createOutgoing'])->name('transactions.create.outgoing');
        Route::post('/transactions/outgoing', [TransactionController::class, 'storeOutgoing'])->name('transactions.store.outgoing');
    });

    // rute untuk manager
    Route::middleware(['role:manager'])->group(function () {
        Route::resource('restock-orders', RestockOrderController::class)->except(['edit', 'update']);
        Route::post('/restock-orders/{restockOrder}/status', [RestockOrderController::class, 'updateStatus'])->name('restock-orders.updateStatus');
    });

     // Grup Rute KHUSUS untuk Supplier
    Route::middleware(['role:supplier'])->prefix('supplier')->name('supplier.')->group(function () {
        Route::get('/dashboard', [SupplierOrderController::class, 'index'])->name('dashboard');
        Route::post('/orders/{restockOrder}/confirm', [SupplierOrderController::class, 'confirm'])->name('orders.confirm');
        Route::post('/orders/{restockOrder}/deny', [SupplierOrderController::class, 'deny'])->name('orders.deny');
    });
});

require __DIR__.'/auth.php';
