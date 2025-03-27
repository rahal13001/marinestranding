<?php

use App\Http\Controllers\MarinespatialController;
use App\Http\Controllers\MarinestrandingController;
use inertia\Inertia;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

// Volt::route('/', 'users.index')->name('mamalia');
// Volt::route('/ruanglaut', 'users.petakkprl')->name('kkprl');

Route::get('/', MarinestrandingController::class)->name('mamalia');
Route::post('/', MarinestrandingController::class)->name('marinestranding');

Route::get('/ruanglaut', MarinespatialController::class)->name('kkprl');
Route::post('/ruanglaut', MarinespatialController::class)->name('kkprl');