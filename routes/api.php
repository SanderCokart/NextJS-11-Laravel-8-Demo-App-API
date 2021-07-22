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
});

//logged in routes
Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\AuthController::class, 'verify'])->name('verification.verify')->middleware('signed');

//verified routes
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

});
