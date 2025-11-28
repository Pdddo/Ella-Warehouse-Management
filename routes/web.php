<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RestockOrderController;
use App\Http\Controllers\SupplierOrderController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });


    // admin onlyz
    Route::middleware('role:admin')->group(function() {
        Route::patch('/admin/suppliers/{id}/approve', [DashboardController::class, 'approveSupplier'])->name('admin.suppliers.approve');
    });


    // supplier onlyz
    Route::middleware('role:supplier')
        ->prefix('supplier/orders')
        ->name('supplier.orders.')
        ->controller(SupplierOrderController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{restockOrder}', 'show')->name('show');
            Route::post('/{restockOrder}/confirm', 'confirm')->name('confirm');
            Route::post('/{restockOrder}/deny', 'deny')->name('deny');
        });


    // admin dan manager
    Route::middleware('role:admin,manager')->group(function () {

        // aksesk ke crud kategori, produk, dan restock order
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('restock-orders', RestockOrderController::class);

        // akses ke update status dan rating restock order
        Route::post('/restock-orders/{restockOrder}/status', [RestockOrderController::class, 'updateStatus'])
            ->name('restock-orders.updateStatus');
        Route::post('/restock-orders/{restockOrder}/rate', [RestockOrderController::class, 'storeRating'])
            ->name('restock-orders.rate');

        // aksesn untuk approve transaksi
        Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])
            ->name('transactions.approve');
    });


    // admin, manager, dan staff
    Route::middleware('role:admin,manager,staff')->group(function () {

        Route::controller(TransactionController::class)
            ->prefix('transactions')
            ->name('transactions.')
            ->group(function () {

                // akses ke daftar dan detail transaksi
                Route::get('/', 'index')->name('index');
                Route::get('/{transaction}', 'show')->whereNumber('transaction')->name('show');

                // akses ke input Barang Masuk (Incoming)
                Route::get('/incoming/create', 'createIncoming')->name('create.incoming');
                Route::post('/incoming', 'storeIncoming')->name('store.incoming');

                // akses ke input Barang Keluar (Outgoing)
                Route::get('/outgoing/create', 'createOutgoing')->name('create.outgoing');
                Route::post('/outgoing', 'storeOutgoing')->name('store.outgoing');

                // akses ke edit & update
                Route::get('/{transaction}/edit', 'edit')->name('edit');
                Route::put('/{transaction}', 'update')->name('update');

                // akses ke hapus
                Route::delete('/{transaction}', 'destroy')->name('destroy');
            });
    });

});

require __DIR__.'/auth.php';
