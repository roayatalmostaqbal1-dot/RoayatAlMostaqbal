<?php

use App\Http\Controllers\Api\V1\SuperAdmin\PermissionController;
use App\Http\Controllers\Api\V1\SuperAdmin\PermissionRoleController;
use App\Http\Controllers\Api\V1\SuperAdmin\RoleController;
use App\Http\Controllers\Api\V1\SuperAdmin\ApiRouteController;
use App\Http\Controllers\Api\V1\SuperAdmin\RolePermissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('SuperAdmin')->middleware(['auth:api', 'role:super-admin'])->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('permissionRole', PermissionRoleController::class);
    Route::apiResource('api-routes', ApiRouteController::class);

    // Role Permissions Management
    Route::prefix('roles/{role}')->group(function () {
        Route::get('/permissions', [RolePermissionController::class, 'getPermissions'])->name('roles.permissions.get');
        Route::post('/permissions', [RolePermissionController::class, 'assignPermissions'])->name('roles.permissions.assign');
        Route::post('/permissions/grant', [RolePermissionController::class, 'grantPermission'])->name('roles.permissions.grant');
        Route::post('/permissions/revoke', [RolePermissionController::class, 'revokePermission'])->name('roles.permissions.revoke');
    });

    // Get all permissions grouped
    Route::get('role-permissions/all', [RolePermissionController::class, 'getAllPermissions'])->name('role-permissions.all');

    // Sync API routes
    Route::post('sync-routes', function () {
        \Artisan::call('permissions:sync');
        return response()->json([
            'message' => 'API routes synced successfully',
            'output' => \Artisan::output(),
        ]);
    })->name('routes.sync');

    Route::prefix('dashboard')->group(function () {
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

});
