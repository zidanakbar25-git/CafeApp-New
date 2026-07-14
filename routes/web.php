<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\AdminMenuController;

/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/table/1?scan=1');
})->name('home');

Route::get('/table/{table}', [MenuController::class, 'index'])->name('menu.index');

Route::get('/cart/{order_id?}', [CartController::class, 'index'])->name('cart.index')->defaults('order_id', 1);
Route::post('/cart/add', [CartController::class, 'addItem'])->name('cart.add');
Route::post('/cart/update-qty-ajax', [CartController::class, 'updateQtyAjax'])->name('cart.updateQtyAjax');
Route::post('/cart/delete-ajax', [CartController::class, 'deleteItemAjax'])->name('cart.deleteItemAjax');
Route::post('/cart/checkout/{order_id}', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/cart/count/{order_id}', [CartController::class, 'getCount'])->name('cart.count');

Route::get('/payment/{order_id}', [PaymentController::class, 'index'])->name('payment.index');
Route::get('/payment/qris/{order_id}', [PaymentController::class, 'qris'])->name('payment.qris');
Route::get('/payment/cash/{order_id}', [PaymentController::class, 'cash'])->name('payment.cash.show');
Route::post('/payment/process/{order_id}', [PaymentController::class, 'process'])->name('payment.process');
Route::get('/payment/cc/{order_id}', [PaymentController::class, 'cc'])->name('payment.cc');
Route::get('/payment/success/{order_id}', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/finalize/{order_id}', [PaymentController::class, 'finalizePayment'])->name('payment.finalize');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AdminLoginController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminLoginController::class, 'login']);
Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Order — semua role
    Route::patch('/admin/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::delete('/admin/orders/{id}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
    Route::get('/admin/orders/{id}/struk', [OrderController::class, 'struk'])->name('admin.orders.struk');
    Route::get('/admin/orders/history', [HistoryController::class, 'index'])->name('admin.orders.history');

    // Manager only
    Route::middleware('role:manager')->prefix('admin')->name('admin.')->group(function () {

        // Manajemen Meja
        Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
        Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
        Route::post('/tables/{table}/clear-history', [TableController::class, 'clearHistory'])->name('tables.clearHistory');
        Route::delete('/tables/{table}', [TableController::class, 'destroy'])->name('tables.destroy');
        Route::get('/tables/{table}/print', [TableController::class, 'print'])->name('tables.print');
        Route::post('/menu/{id}/toggle-active', [AdminMenuController::class, 'toggleActive'])->name('menu.toggleActive');

        // Manajemen Menu
        Route::get('/menu', [AdminMenuController::class, 'index'])->name('menu.index');
        Route::get('/menu/create', [AdminMenuController::class, 'create'])->name('menu.create');
        Route::post('/menu', [AdminMenuController::class, 'store'])->name('menu.store');
        Route::get('/menu/{id}/edit', [AdminMenuController::class, 'edit'])->name('menu.edit');
        Route::put('/menu/{id}', [AdminMenuController::class, 'update'])->name('menu.update');
        Route::delete('/menu/{id}', [AdminMenuController::class, 'destroy'])->name('menu.destroy');
        Route::get('/get-subcategories/{categoryId}', [AdminMenuController::class, 'getSubCategories'])->name('menu.getSubCategories');

        // Manajemen Admin
        Route::get('/admins', [AdminManagementController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminManagementController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminManagementController::class, 'store'])->name('admins.store');
        Route::get('/admins/{id}/edit', [AdminManagementController::class, 'edit'])->name('admins.edit');
        Route::put('/admins/{id}', [AdminManagementController::class, 'update'])->name('admins.update');
        Route::delete('/admins/{id}', [AdminManagementController::class, 'destroy'])->name('admins.destroy');
        Route::post('/admins/{id}/reset-password', [AdminManagementController::class, 'resetPassword'])->name('admins.resetPassword');

        //token
        Route::post('/cart/add', [CartController::class, 'addItem'])
            ->name('cart.add')
            ->middleware('table.session');

        Route::post('/cart/update-qty-ajax', [CartController::class, 'updateQtyAjax'])->middleware('table.session');
        Route::post('/cart/checkout/{order_id}', [CartController::class, 'checkout'])->middleware('table.session');
    });
});
