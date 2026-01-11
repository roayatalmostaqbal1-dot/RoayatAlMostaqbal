<?php

use App\Http\Controllers\Api\V1\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('notifications')->middleware('auth:api')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::post('{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('{id}', [NotificationController::class, 'destroy']);
});
