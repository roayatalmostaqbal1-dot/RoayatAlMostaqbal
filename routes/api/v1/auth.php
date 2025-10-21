<?php

use App\Http\Controllers\Api\V1\AuthenticationController;
use App\Http\Resources\Api\V1\User\UserInfoResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function () {
        $user = Auth::user();
        return new UserInfoResource($user);
    });
    Route::post('logout', [AuthenticationController::class, 'logOut'])->name('logout');

});

Route::post('register', [AuthenticationController::class, 'register'])->name('register');
Route::post('login', [AuthenticationController::class, 'login']);
