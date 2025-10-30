<?php

use Illuminate\Support\Facades\Route;

// Extremely simple test route
Route::get('/simple-test', function () {
    return 'Laravel is working! ' . date('Y-m-d H:i:s');
});

// Test route that shows basic info
Route::get('/info', function () {
    return response()->json([
        'status' => 'working',
        'timestamp' => now(),
        'php_version' => phpversion(),
        'laravel_version' => app()->version(),
        'environment' => app()->environment(),
    ]);
});

// Test if we can handle a basic view
Route::get('/view-test', function () {
    return view('welcome');
});