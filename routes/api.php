<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//guest routes
Route::group([], function () {
    Route::post('/sign-up', [App\Http\Controllers\AuthController::class, 'sign_up']);
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::get('/check', [App\Http\Controllers\AuthController::class, 'check']);
    Route::post('/forget-password', [App\Http\Controllers\AuthController::class, 'forget_password']);
    Route::patch('/reset-password', [\App\Http\Controllers\AuthController::class, 'reset_password'])->name('password.reset');
});

//logged in routes
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\AuthController::class, 'verify'])->name('verification.verify')->middleware('signed');
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});

//verified routes
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

});
