<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ContactController;

// Language switching
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Main pages with locale support
Route::group(['prefix' => '{locale?}', 'where' => ['locale' => 'ar|en']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/services', [ServicesController::class, 'index'])->name('services');
    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
 

    // Contact form submission
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    // Newsletter subscription
    Route::post('/newsletter', [HomeController::class, 'newsletter'])->name('newsletter.subscribe');
});

// Redirect root to Arabic
Route::get('/', function () {
    return redirect('/ar');
});
