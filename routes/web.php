<?php

use Illuminate\Support\Facades\Route;

// Catch-all route - Vue.js handles all frontend routing
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');
