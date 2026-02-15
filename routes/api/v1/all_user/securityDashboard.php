<?php

use App\Http\Controllers\Api\V1\SecurityDashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('security-dashboard')->middleware(['auth:api'])->group(function () {
    // Standard dashboard endpoints
    Route::get('/', [SecurityDashboardController::class, 'index'])->name('security-dashboard.index');
    Route::get('/identity', [SecurityDashboardController::class, 'identityData'])->name('security-dashboard.identity');
    Route::get('/security-logs', [SecurityDashboardController::class, 'securityLogs'])->name('security-dashboard.logs');
    Route::get('/ai-analysis', [SecurityDashboardController::class, 'aiAnalysis'])->name('security-dashboard.ai');
    Route::get('/system-metrics', [SecurityDashboardController::class, 'systemMetrics'])->name('security-dashboard.metrics');
    Route::post('/generate-logs', [SecurityDashboardController::class, 'generateLogs'])->name('security-dashboard.generate-logs');
    Route::post('/generate-insight', [SecurityDashboardController::class, 'generateInsight'])->name('security-dashboard.generate-insight');
    Route::post('/export-logs', [SecurityDashboardController::class, 'exportLogs'])->name('security-dashboard.export-logs');

    // Encryption demo endpoints - show encrypted data as stored in database
    Route::get('/encrypted-raw', [SecurityDashboardController::class, 'encryptedRaw'])->name('security-dashboard.encrypted-raw');
    Route::get('/encrypted-all', [SecurityDashboardController::class, 'encryptedAll'])->name('security-dashboard.encrypted-all');
    Route::get('/encryption-metadata', [SecurityDashboardController::class, 'encryptionMetadata'])->name('security-dashboard.encryption-metadata');
});
