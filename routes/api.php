<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Live Tracking API Routes (can be called without auth for testing)
Route::get('/order/{order}/track', [OrderController::class, 'apiTrackOrder'])->name('api.order.track');
Route::post('/order/{order}/update-location', [OrderController::class, 'apiUpdateLocation'])->name('api.order.updateLocation');
