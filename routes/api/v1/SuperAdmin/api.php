<?php

use App\Http\Controllers\Api\V1\SuperAdmin\PermissionController;
use App\Http\Controllers\Api\V1\SuperAdmin\PermissionRoleController;
use App\Http\Controllers\Api\V1\SuperAdmin\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('SuperAdmin')->middleware(['auth:api', 'role:super-admin'])->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('permissionRole', PermissionRoleController::class);
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
