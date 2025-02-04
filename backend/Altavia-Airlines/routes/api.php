<?php

use App\Http\Controllers\Api\AirplaneController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

// USERS
Route::get('/users', [UserController::class, 'index'])->name('apiHomeUsers');
Route::get('/users/{id}', [UserController::class, 'show'])->name('apiShowUser');
Route::post('/users', [UserController::class, 'store'])->name('apiStoreUser');
Route::put('/users/{id}', [UserController::class, 'update'])->name('apiUpdateUser');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('apiDestroyUser');

// CITIES
Route::get('/cities', [CityController::class, 'index'])->name('apiHomeCities');
Route::get('/cities/{id}', [CityController::class, 'show'])->name('apiShowCity');
Route::post('/cities', [CityController::class, 'store'])->name('apiStoreCity');
Route::put('/cities/{id}', [CityController::class, 'update'])->name('apiUpdateCity');
Route::delete('/cities/{id}', [CityController::class, 'destroy'])->name('apiDestroyCity');

// AIRPLANES
Route::get('/airplanes', [AirplaneController::class, 'index'])->name('apiHomeAirplanes');
Route::get('/airplanes/{id}', [AirplaneController::class, 'show'])->name('apiShowAirplane');
Route::post('/airplanes', [AirplaneController::class, 'store'])->name('apiStoreAirplane');
Route::put('/airplanes/{id}', [AirplaneController::class, 'update'])->name('apiUpdateAirplane');
Route::delete('/airplanes/{id}', [AirplaneController::class, 'destroy'])->name('apiDestroyAirplane');