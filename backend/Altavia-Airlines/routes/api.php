<?php

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