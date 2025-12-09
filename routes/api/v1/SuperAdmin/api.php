<?php

use App\Http\Controllers\Api\V1\SuperAdmin\PermissionController;
use App\Http\Controllers\Api\V1\SuperAdmin\PermissionRoleController;
use App\Http\Controllers\Api\V1\SuperAdmin\RoleController;
use App\Http\Controllers\Api\V1\SuperAdmin\RolePermissionController;
use App\Http\Controllers\Api\V1\SuperAdmin\Dashboard\DashboardController;
use App\Http\Controllers\Api\V1\SuperAdmin\OAuth2ClientController;
use Illuminate\Support\Facades\Route;
Route::prefix('SuperAdmin')->middleware(['auth:api','role:super-admin'])->group(function () {
    // =====================
    // Roles Management
    // =====================
    Route::apiResource('roles', RoleController::class, [
        'middleware' => [
            'index' => 'permission:roles.view',
            'show' => 'permission:roles.view',
            'store' => 'permission:roles.create',
            'update' => 'permission:roles.edit',
            'destroy' => 'permission:roles.delete',
        ]
    ]);

    // =====================
    // Permissions Management
    // =====================
    Route::apiResource('permissions', PermissionController::class, [
        'middleware' => [
            'index' => 'permission:permissions.view',
            'show' => 'permission:permissions.view',
            'store' => 'permission:permissions.create',
            'update' => 'permission:permissions.edit',
            'destroy' => 'permission:permissions.delete',
        ]
    ]);

    // =====================
    // Permission-Role Assignment
    // =====================
    Route::apiResource('permissionRole', PermissionRoleController::class, [
        'middleware' => [
            'index' => 'permission:roles.edit',
            'show' => 'permission:roles.edit',
            'store' => 'permission:roles.edit',
            'update' => 'permission:roles.edit',
            'destroy' => 'permission:roles.edit',
        ]
    ]);


    // =====================
    // Role Permissions Management
    // =====================
    Route::prefix('roles/{role}')->middleware('permission:roles.edit')->group(function () {
        Route::get('/permissions', [RolePermissionController::class, 'getPermissions'])->name('roles.permissions.get');
        Route::post('/permissions', [RolePermissionController::class, 'assignPermissions'])->name('roles.permissions.assign');
        Route::post('/permissions/grant', [RolePermissionController::class, 'grantPermission'])->name('roles.permissions.grant');
        Route::post('/permissions/revoke', [RolePermissionController::class, 'revokePermission'])->name('roles.permissions.revoke');
    });

    // Get all permissions grouped
    Route::get('role-permissions/all', [RolePermissionController::class, 'getAllPermissions'])
        ->middleware('permission:roles.edit')
        ->name('role-permissions.all');


    // =====================
    // Dashboard Routes
    // =====================
    Route::prefix('dashboard')->middleware('permission:dashboard.view')->group(function () {
        Route::get('/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');
        Route::get('/users-stats', fn() => response()->json([
            'labels' => [
                '09-01-2024', '10-01-2024', '11-01-2024',
                '12-01-2024', '01-01-2025',
            ],
            'active_users' => [120, 150, 17022, 210, 260, 300],
            'inactive_users' => [20, 30, 25, 40, 35, 45],
        ]));
    });

    // =====================
    // OAuth2 Clients Management
    // =====================
    Route::apiResource('oauth2-clients', OAuth2ClientController::class, [
        // 'middleware' => [
        //     'index' => 'permission:oauth2_clients.view|role:super-admin',
        //     'show' => 'permission:oauth2_clients.view|role:super-admin',
        //     'store' => 'permission:oauth2_clients.create|role:super-admin',
        //     'update' => 'permission:oauth2_clients.edit|role:super-admin',
        //     'destroy' => 'permission:oauth2_clients.delete|role:super-admin',
        // ]
    ]);

    // Regenerate OAuth2 Client Secret
    Route::post('oauth2-clients/{id}/regenerate-secret', [OAuth2ClientController::class, 'regenerateSecret'])
        // ->middleware('permission:oauth2_clients.edit|role:super-admin')
        ->name('oauth2-clients.regenerate-secret');

    // =====================
    // Two-Factor Authentication Routes
    // =====================


});
