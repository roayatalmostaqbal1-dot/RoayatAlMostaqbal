<?php

use App\Http\Controllers\Api\V1\SuperAdmin\PermissionController;
use App\Http\Controllers\Api\V1\SuperAdmin\PermissionRoleController;
use App\Http\Controllers\Api\V1\SuperAdmin\RoleController;
use App\Http\Controllers\Api\V1\SuperAdmin\RolePermissionController;
use App\Http\Controllers\Api\V1\SuperAdmin\UserController;
use App\Http\Controllers\Api\V1\SuperAdmin\Dashboard\DashboardController;
use App\Http\Controllers\Api\V1\TwoFactorAuthController;

use Illuminate\Support\Facades\Route;

Route::prefix('SuperAdmin')->middleware(['auth:api'])->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('permissionRole', PermissionRoleController::class);
    Route::apiResource('users', UserController::class);
    // Role Permissions Management
    Route::prefix('roles/{role}')->group(function () {
        Route::get('/permissions', [RolePermissionController::class, 'getPermissions'])->name('roles.permissions.get');
        Route::post('/permissions', [RolePermissionController::class, 'assignPermissions'])->name('roles.permissions.assign');
        Route::post('/permissions/grant', [RolePermissionController::class, 'grantPermission'])->name('roles.permissions.grant');
        Route::post('/permissions/revoke', [RolePermissionController::class, 'revokePermission'])->name('roles.permissions.revoke');
    });

    // Get all permissions grouped
    Route::get('role-permissions/all', [RolePermissionController::class, 'getAllPermissions'])->name('role-permissions.all');


    Route::prefix('dashboard')->group(function () {
        Route::get('/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');
        Route::get('/users-stats', function () {
            return response()->json([
                'labels' => [
                    '09-01-2024', '10-01-2024', '11-01-2024',
                    '12-01-2024', '01-01-2025',
                ],
                'active_users' => [120, 150, 17022, 210, 260, 300],
                'inactive_users' => [20, 30, 25, 40, 35, 45],
            ]);

        });
    });

    // Two-Factor Authentication Routes
    Route::prefix('two-factor')->group(function () {
        Route::get('/status', [TwoFactorAuthController::class, 'status'])->name('two-factor.status');
        Route::post('/enable', [TwoFactorAuthController::class, 'enable'])->name('two-factor.enable');
        Route::post('/confirm', [TwoFactorAuthController::class, 'confirm'])->name('two-factor.confirm');
        Route::post('/disable', [TwoFactorAuthController::class, 'disable'])->name('two-factor.disable');
        // Route::post('/verify', [TwoFactorAuthController::class, 'verify'])->name('two-factor.verify');
        Route::post('/recovery-codes', [TwoFactorAuthController::class, 'generateRecoveryCodes'])->name('two-factor.recovery-codes');
    });

});
