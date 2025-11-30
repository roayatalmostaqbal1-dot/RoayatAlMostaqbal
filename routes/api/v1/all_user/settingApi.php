<?php

use App\Http\Controllers\Api\V1\AuthenticationController;
use App\Http\Controllers\Api\V1\TwoFactorAuthController;
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
});
