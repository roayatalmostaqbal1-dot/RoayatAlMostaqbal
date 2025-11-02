<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Support\TelegramController;


 Route::prefix('support/telegram')->group(function () {
    Route::post('webhook', [TelegramController::class, 'webhook']);
});
