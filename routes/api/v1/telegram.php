<?php

use App\Http\Controllers\Api\V1\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

// Public webhook - no authentication required
Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handleWebhook'])
    ->name('telegram.webhook');
