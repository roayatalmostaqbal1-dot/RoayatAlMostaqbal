<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SocialAuthController;



Route::get('/auth/{provider}', [SocialAuthController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);
