<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{
    LanguageController,
    HomeController,
    AboutController,
    ServicesController,
    ProjectsController,
    ContactController,
    SecurityController,
};
use Telegram\Bot\Laravel\Facades\Telegram;

// Language switching
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Main pages with locale support (Government Compliant Routes)
Route::group(['prefix' => '{locale?}', 'where' => ['locale' => 'ar|en']], function () {
    // Core public pages
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/services', [ServicesController::class, 'index'])->name('services');
    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');

    // Contact form submission (with CSRF protection)
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    // Newsletter subscription
    Route::post('/newsletter', [HomeController::class, 'newsletter'])->name('newsletter.subscribe');

    // Security & Government Compliance Pages
    Route::prefix('security')->name('security.')->group(function () {
        // Encryption methodology and Zero-Knowledge Proof explanation
        Route::get('/encryption', [SecurityController::class, 'encryption'])->name('encryption');
        // Privacy policy page
        Route::get('/privacy', [SecurityController::class, 'privacy'])->name('privacy');
        // Data protection policy page
        Route::get('/data-protection', [SecurityController::class, 'dataProtection'])->name('data-protection');
        // Download ZKP verification report (PDF)
        Route::get('/verification-report', [SecurityController::class, 'downloadVerificationReport'])->name('verification-report');
    });
});

Route::get('/admin/{vue_capture?}', function () {
    putenv('APP_LOCALE=en');
    return view('vue.app');
})->where('vue_capture', '[\/\w\.-]*');
// Redirect root to Arabic
Route::get('/', function () {
    return redirect('https://www.roayatalmostaqbal.net/ar', 301);
});
