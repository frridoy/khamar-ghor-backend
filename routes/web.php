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

    // System Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminSystemSettingController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\AdminSystemSettingController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Admin\AdminSystemSettingController::class, 'store'])->name('store');
        Route::get('/{setting}', [\App\Http\Controllers\Admin\AdminSystemSettingController::class, 'show'])->name('show');
        Route::put('/{setting}', [\App\Http\Controllers\Admin\AdminSystemSettingController::class, 'update'])->name('update');
    });

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

    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminPostController::class, 'index'])->name('index');
        Route::get('/media', [\App\Http\Controllers\Admin\AdminPostController::class, 'media'])->name('media');
        Route::get('/create', [\App\Http\Controllers\Admin\AdminPostController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Admin\AdminPostController::class, 'store'])->name('store');
        Route::get('/{post}/edit', [\App\Http\Controllers\Admin\AdminPostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [\App\Http\Controllers\Admin\AdminPostController::class, 'update'])->name('update');
        Route::get('/{post}/view', [\App\Http\Controllers\Admin\AdminPostController::class, 'show'])->name('show');
        Route::delete('/{post}', [\App\Http\Controllers\Admin\AdminPostController::class, 'destroy'])->name('delete');

        // AJAX helpers
        Route::get('/get-stores/{userId}', [\App\Http\Controllers\Admin\AdminPostController::class, 'getStores'])->name('get-stores');
        Route::get('/get-attributes/{categoryId}', [\App\Http\Controllers\Admin\AdminPostController::class, 'getAttributes'])->name('get-attributes');
    });

    Route::prefix('stores')->name('stores.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminStoreController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\AdminStoreController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Admin\AdminStoreController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\AdminStoreController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Admin\AdminStoreController::class, 'update'])->name('update');
        Route::get('/{id}/view', [\App\Http\Controllers\Admin\AdminStoreController::class, 'show'])->name('show');
        Route::post('/{id}/verify', [\App\Http\Controllers\Admin\AdminStoreController::class, 'verify'])->name('verify');
    });

    // Location API Routes
    Route::get('/divisions/{countryId}', function ($countryId) {
        return response()->json(\App\Models\Division::where('country_id', $countryId)->orderBy('name_en')->get(['id', 'name_en as name']));
    });
    Route::get('/districts/{divisionId}', function ($divisionId) {
        return response()->json(\App\Models\District::where('division_id', $divisionId)->orderBy('name_en')->get(['id', 'name_en as name']));
    });
    Route::get('/thanas/{districtId}', function ($districtId) {
        return response()->json(\App\Models\Thana::where('district_id', $districtId)->orderBy('name_en')->get(['id', 'name_en as name']));
    });
});

Route::get('/customer/view/{id}', [CustomerController::class, 'show'])->name('admin.customers.show');
