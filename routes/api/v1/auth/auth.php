<?php

use App\Http\Controllers\Api\V1\AuthenticationController;
use App\Http\Controllers\Api\V1\TwoFactorAuthController;
use App\Http\Controllers\Api\V1\EncryptedDataController;
use App\Http\Resources\Api\V1\User\UserInfoResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function () {
        $user = Auth::user();
        return new UserInfoResource($user);
    });
    Route::post('logout', [AuthenticationController::class, 'logOut'])->name('logout');

    // Encrypted data routes
    Route::post('/encrypted-data', [EncryptedDataController::class, 'store']);
    Route::get('/encrypted-data', [EncryptedDataController::class, 'show']);
    Route::put('/encrypted-data/{id}', [EncryptedDataController::class, 'update']);
    Route::get('/admin/encrypted-data/{userId}', [EncryptedDataController::class, 'adminShow']);
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthenticationController::class, 'register'])->name('register');
    Route::post('login', [AuthenticationController::class, 'login']);
    Route::post('/two-factor/verify', [TwoFactorAuthController::class, 'verify']);

});

