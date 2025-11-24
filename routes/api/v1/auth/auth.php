<?php

use App\Http\Controllers\Api\V1\AuthenticationController;
use App\Http\Controllers\Api\V1\TwoFactorAuthController;
use App\Http\Controllers\Api\V1\EncryptedDataController;
use App\Http\Resources\Api\V1\User\UserInfoResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    // =====================
    // User Profile Routes
    // =====================
    Route::get('/user', fn() => new UserInfoResource(Auth::user()));
    Route::post('logout', [AuthenticationController::class, 'logOut'])->name('logout');

    // =====================
    // Encrypted Data Routes
    // =====================
    Route::post('/encrypted-data', [EncryptedDataController::class, 'store'])
        ->middleware('permission:encrypted_data.create');
    Route::get('/encrypted-data', [EncryptedDataController::class, 'show'])
        ->middleware('permission:encrypted_data.view');
    Route::put('/encrypted-data/{id}', [EncryptedDataController::class, 'update'])
        ->middleware('permission:encrypted_data.edit');
    Route::get('/admin/encrypted-data/{userId}', [EncryptedDataController::class, 'adminShow'])
        ->middleware('permission:encrypted_data.view');
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthenticationController::class, 'register'])->name('register');
    Route::post('login', [AuthenticationController::class, 'login']);
    Route::post('/two-factor/verify', [TwoFactorAuthController::class, 'verify']);

});

