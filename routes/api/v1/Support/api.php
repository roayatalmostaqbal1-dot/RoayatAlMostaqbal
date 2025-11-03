<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Support\TelegramController;
use Telegram\Bot\Laravel\Facades\Telegram;


 Route::prefix('support/telegram')->group(function () {
    Route::post('webhook', [TelegramController::class, 'webhook']);
    Route::get('test', function () {
            return Telegram::getUpdates();
        });

});
