<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OfferController as V1OfferController;

// Redirect /api/doc to /api/documentation for convenience
Route::redirect('/doc', '/api/documentation');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Version 1
Route::prefix('v1')->group(function () {
    // Public endpoint to list published offers
    Route::get('/offers', [V1OfferController::class, 'index']);
});

// Backward compatibility: redirect old endpoint to v1
Route::get('/offers', function () {
    return redirect('/api/v1/offers');
});
