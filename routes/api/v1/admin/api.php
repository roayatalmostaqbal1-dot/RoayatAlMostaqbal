<?php

use App\Http\Controllers\Api\V1\Admin\{
    UserController,
    ContactController,
    Dashboard\DashboardController
};
use Illuminate\Support\Facades\Route;



Route::prefix('admin')->middleware(['auth:api'])->group(function () {
    // =====================
    // Users Management (Admin)
    // =====================
    
    Route::apiResource('users', UserController::class, [
        'middleware' => [
            'index' => 'permission:users.view',
            'show' => 'permission:users.view',
            'store' => 'permission:users.create',
            'update' => 'permission:users.edit',
            'destroy' => 'permission:users.delete',
        ]
    ]);

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
    // Contact Management
    // =====================

    Route::apiResource('contacts', ContactController::class, [
        'middleware' => [
            'index' => 'permission:contacts.view',
            'show' => 'permission:contacts.view',
            'update' => 'permission:contacts.edit',
            'destroy' => 'permission:contacts.delete',
        ]
    ]);
});
