<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CustomerController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth', 'admin_access'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('customer')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/store', [CustomerController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CustomerController::class, 'destroy'])->name('delete');
    });

    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'store'])->name('store');
        Route::get('/{category}/view', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'destroy'])->name('delete');

        Route::prefix('attributes')->name('attributes.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminCategoryAttributeController::class, 'globalIndex'])->name('global');
        });

        Route::prefix('{category}/attributes')->name('attributes.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminCategoryAttributeController::class, 'index'])->name('index');
            Route::get('/manage', [\App\Http\Controllers\Admin\AdminCategoryAttributeController::class, 'manage'])->name('manage');
            Route::post('/update-bulk', [\App\Http\Controllers\Admin\AdminCategoryAttributeController::class, 'updateBulk'])->name('update-bulk');
            Route::get('/create', [\App\Http\Controllers\Admin\AdminCategoryAttributeController::class, 'create'])->name('create');
            Route::post('/store', [\App\Http\Controllers\Admin\AdminCategoryAttributeController::class, 'store'])->name('store');
            Route::get('/{attribute}/edit', [\App\Http\Controllers\Admin\AdminCategoryAttributeController::class, 'edit'])->name('edit');
            Route::put('/{attribute}', [\App\Http\Controllers\Admin\AdminCategoryAttributeController::class, 'update'])->name('update');
            Route::delete('/{attribute}', [\App\Http\Controllers\Admin\AdminCategoryAttributeController::class, 'destroy'])->name('delete');
        });
    });
});

Route::get('/customer/view/{id}', [CustomerController::class, 'show'])->name('admin.customers.show');
