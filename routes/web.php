<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RestockOrderController;
use App\Http\Controllers\SupplierOrderController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


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
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        
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
        Route::resource('categories', CategoryController::class);
        Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
    });

     // Grup Rute KHUSUS untuk Supplier
    Route::middleware(['auth', 'verified', 'role:supplier'])->group(function () {
        Route::post('/supplier/orders/{restockOrder}/confirm', [SupplierOrderController::class, 'confirm'])->name('supplier.orders.confirm');
        Route::post('/supplier/orders/{restockOrder}/deny', [SupplierOrderController::class, 'deny'])->name('supplier.orders.deny');
    });
});

require __DIR__.'/auth.php';
