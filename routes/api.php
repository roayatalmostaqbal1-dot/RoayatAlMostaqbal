<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    foreach (glob(__DIR__.'/api/v1/*.php') as $filename) {
        require $filename;
    }
    foreach (glob(__DIR__.'/api/v1/**/*.php') as $filename) {
        require $filename;
    }
});
