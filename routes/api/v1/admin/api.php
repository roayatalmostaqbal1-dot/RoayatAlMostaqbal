<?php

use App\Http\Controllers\Api\V1\Admin\UserController;
use Illuminate\Support\Facades\{Auth,Route};

Route::prefix('admin')->middleware(['auth:api','role:admin|super-admin'])->group(function () {
    Route::apiResource('users', UserController::class);
});
