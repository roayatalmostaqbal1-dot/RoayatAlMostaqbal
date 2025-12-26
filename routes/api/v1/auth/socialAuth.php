<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\SocialAuthController;



Route::prefix('social-auth')->group(function () {
    Route::get('/{provider}', [SocialAuthController::class, 'redirectToProvider']);
    Route::get('/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);
});
