<?php

use App\Http\Controllers\Api\V1\Admin\UserController;
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
});
