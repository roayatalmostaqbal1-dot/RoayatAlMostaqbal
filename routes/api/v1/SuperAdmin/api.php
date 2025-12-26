<?php

use App\Http\Controllers\Api\V1\SuperAdmin\{
    RolePermissionController,
    PermissionController,
    RoleController,
    PermissionRoleController,
    PageController,
    OAuth2ClientController
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
    // OAuth2 Clients Management
    // =====================

    Route::apiResource('oauth2-clients', OAuth2ClientController::class, [
        'middleware' => [
            'index' => 'permission:oauth2_clients.view',
            'show' => 'permission:oauth2_clients.view',
            'store' => 'permission:oauth2_clients.create',
            'update' => 'permission:oauth2_clients.edit',
            'destroy' => 'permission:oauth2_clients.delete',
        ]
    ]);

    Route::post('oauth2-clients/{id}/regenerate-secret', [OAuth2ClientController::class, 'regenerateSecret'])
        ->middleware('permission:oauth2_clients.edit')
        ->name('oauth2-clients.regenerate-secret');



    // =====================
    // Pages Management
    // =====================

    Route::apiResource('pages', PageController::class, [
        'only' => ['index', 'show']
    ])->middleware('permission:pages.view');
    Route::get('pages-with-roles/all', [PageController::class, 'getAllWithRoles'])
        ->name('pages.all-with-roles')->middleware('permission:pages.view');
    Route::post('roles/{role}/pages', [PageController::class, 'assignPagesToRole'])
        ->name('roles.pages.assign')->middleware('permission:roles.edit');
    Route::get('roles/{role}/pages', [PageController::class, 'getPagesForRole'])
        ->name('roles.pages.get')->middleware('permission:roles.edit');
    Route::delete('roles/{role}/pages/{pageKey}', [PageController::class, 'removePageFromRole'])
        ->name('roles.pages.remove')->middleware('permission:roles.edit');



});
