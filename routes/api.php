<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AdminController;

// Route yang tidak butuh token (publik)
Route::post('/login', [AuthController::class, 'login']);

// Group route yang butuh token (terproteksi)
Route::group(['middleware' => 'auth:api'], function () {
    // Nanti, route seperti logout, me, refresh, dll. akan ditaruh di sini.
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth.apikey')->prefix('admin')->group(function () {
    Route::post('/reset-password', [AdminController::class, 'resetPassword']);
});
