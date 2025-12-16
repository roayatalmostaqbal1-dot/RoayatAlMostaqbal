<?php

use App\Http\Controllers\Api\V1\SuperAdmin\{Dashboard\DashboardController,
    ContactController,
    OAuth2ClientController,
    RolePermissionController,
    PermissionController,
    RoleController,
    PermissionRoleController,
    PageController
 };

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
    // Contact Management
    // =====================
    Route::apiResource('contacts', ContactController::class, [
        // 'middleware' => [
        //     'index' => 'permission:contacts.view|role:super-admin',
        //     'show' => 'permission:contacts.view|role:super-admin',
        //     'update' => 'permission:contacts.edit|role:super-admin',
        //     'destroy' => 'permission:contacts.delete|role:super-admin',
        // ]
    ]);

    // =====================
    // Pages Management
    // =====================
    Route::apiResource('pages', PageController::class, [
        'only' => ['index', 'show']
    ]);
    Route::get('pages-with-roles/all', [PageController::class, 'getAllWithRoles'])
        ->name('pages.all-with-roles');
    Route::post('roles/{role}/pages', [PageController::class, 'assignPagesToRole'])
        ->name('roles.pages.assign');
    Route::get('roles/{role}/pages', [PageController::class, 'getPagesForRole'])
        ->name('roles.pages.get');
    Route::delete('roles/{role}/pages/{pageKey}', [PageController::class, 'removePageFromRole'])
        ->name('roles.pages.remove');

    // =====================
    // Two-Factor Authentication Routes
    // =====================


});
