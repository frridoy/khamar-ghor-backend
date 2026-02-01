<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\LocationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::middleware('is_super_admin')->group(function () {
        Route::get('/users', [UserController::class, 'getAllUsers']);
        Route::get('/get-user-roles', [AuthController::class, 'getUserRoles']);
    });
    
    Route::post('/store-user-profile', [UserProfileController::class, 'store']);
    Route::post('/update-user-profile', [UserProfileController::class, 'updateProfile']);
    Route::get('/get-user-profile/{user_id}', [UserProfileController::class, 'getProfile']);
});

Route::controller(LocationController::class)->group(function () {
    Route::get('/countries', 'countries');
    Route::get('/divisions/{country_id}', 'divisions');
    Route::get('/districts/{division_id}', 'districts');
    Route::get('/thanas/{district_id}', 'thanas');
});
