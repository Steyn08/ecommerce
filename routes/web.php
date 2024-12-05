<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('home');
// });

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::prefix('admin')->middleware(['role:1', 'auth'])->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductsController::class, 'index'])->name('admin.products');
        Route::get('add', [ProductsController::class, 'create'])->name('admin.products.add');
        Route::get('edit/{id}', [ProductsController::class, 'edit'])->name('admin.products.edit');
        Route::get('view/{id}', [ProductsController::class, 'show'])->name('admin.products.view');
        Route::post('store', [ProductsController::class, 'store'])->name('admin.products.store');
        Route::post('update/{id}', [ProductsController::class, 'update'])->name('admin.products.update');
        Route::post('delete/{id}', [ProductsController::class, 'delete'])->name('admin.products.delete');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
