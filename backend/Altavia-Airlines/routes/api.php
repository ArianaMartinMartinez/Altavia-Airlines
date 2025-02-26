<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\AirplaneController;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});


Route::middleware(['auth:api', 'isAdmin'])->group(function() {
    // USERS
    Route::get('/users', [UserController::class, 'index'])->name('apiHomeUsers');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('apiShowUser');
    Route::post('/users', [UserController::class, 'store'])->name('apiStoreUser');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('apiUpdateUser');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('apiDestroyUser');

    // CITIES
    Route::post('/cities', [CityController::class, 'store'])->name('apiStoreCity');
    Route::put('/cities/{id}', [CityController::class, 'update'])->name('apiUpdateCity');
    Route::delete('/cities/{id}', [CityController::class, 'destroy'])->name('apiDestroyCity');
    
    // AIRPLANES
    Route::get('/airplanes', [AirplaneController::class, 'index'])->name('apiHomeAirplanes');
    Route::get('/airplanes/{id}', [AirplaneController::class, 'show'])->name('apiShowAirplane');
    Route::post('/airplanes', [AirplaneController::class, 'store'])->name('apiStoreAirplane');
    Route::put('/airplanes/{id}', [AirplaneController::class, 'update'])->name('apiUpdateAirplane');
    Route::delete('/airplanes/{id}', [AirplaneController::class, 'destroy'])->name('apiDestroyAirplane');
    
    // FLIGHTS
    Route::get('/flights/{id}', [FlightController::class, 'show'])->name('apiShowFlight');
    Route::post('/flights', [FlightController::class, 'store'])->name('apiStoreFlight');
    Route::put('/flights/{id}', [FlightController::class, 'update'])->name('apiUpdateFlight');
    Route::delete('/flights/{id}', [FlightController::class, 'destroy'])->name('apiDestroyFlight');
});

Route::get('/cities', [CityController::class, 'index'])->name('apiHomeCities');
Route::get('/cities/{id}', [CityController::class, 'show'])->name('apiShowCity');

Route::get('/flightsFuture', [FlightController::class, 'indexFutureFlights'])->name('apiFutureFlights');
Route::get('/flightsPast', [FlightController::class, 'indexPastFlights'])->name('apiPastFlights');
Route::get('/filterFutureFlights', [FlightController::class, 'filterFutureFlights'])->name('apiFiltereFutureFlights');
Route::get('/filterPastFlights', [FlightController::class, 'filterPastFlights'])->name('apiFilterePastFlights');

Route::get('/bookings', [FlightController::class, 'indexBookings'])->name('apiHomeBookings');
Route::post('/book/{id}', [FlightController::class, 'bookFlight'])->middleware('auth:api')->name('apiBookFlight');
Route::post('/cancel/{id}', [FlightController::class, 'cancelFlight'])->middleware('auth:api')->name('apiCancelFlight');