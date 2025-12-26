<?php

use App\Http\Controllers\Api\V1\AllUser\EncryptedDataController;
use App\Http\Controllers\Api\V1\AllUser\TwoFactorAuthController;
use App\Http\Controllers\Api\V1\Auth\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::prefix('two-factor')->group(function () {
        Route::get('/status', [TwoFactorAuthController::class, 'status'])->name('two-factor.status');
        Route::post('/enable', [TwoFactorAuthController::class, 'enable'])
            ->name('two-factor.enable');
        Route::post('/confirm', [TwoFactorAuthController::class, 'confirm'])
            ->name('two-factor.confirm');
        Route::post('/disable', [TwoFactorAuthController::class, 'disable'])
            ->name('two-factor.disable');
        Route::post('/recovery-codes', [TwoFactorAuthController::class, 'generateRecoveryCodes'])
            ->name('two-factor.recovery-codes');
    });
    Route::post('change-password', [AuthenticationController::class, 'changePassword'])->name('change-password');

    Route::post('/encrypted-data', [EncryptedDataController::class, 'store'])
        ->middleware('permission:encrypted_data.create');
    Route::get('/encrypted-data', [EncryptedDataController::class, 'show'])
        ->middleware('permission:encrypted_data.view');
    Route::put('/encrypted-data/{id}', [EncryptedDataController::class, 'update'])
        ->middleware('permission:encrypted_data.edit');
    Route::get('/admin/encrypted-data/{userId}', [EncryptedDataController::class, 'adminShow'])
        ->middleware('permission:encrypted_data.view');

    
});
