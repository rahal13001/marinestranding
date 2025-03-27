<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\KkprluseController;
use App\Http\Controllers\API\GeoJsonController;
use App\Http\Controllers\API\KkprlmapController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('throttle:public-api')->group(function () {
    Route::get('/rzwp3k', [KkprlmapController::class, 'rzwp3k'])->name('rzwp3k');
    Route::get('/kkprl', [KkprluseController::class, 'kkprl'])->name('kkprl');
    // Route::get('/storage/{filename}', [GeoJsonController::class, 'serveGeoJson']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

