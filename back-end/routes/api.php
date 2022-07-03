<?php

use Illuminate\Http\Request;
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

Route::post('auth/login', [\App\Http\Controllers\Api\Auth::class, 'login']);
Route::post('auth/register', [\App\Http\Controllers\Api\Auth::class, 'register']);


Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
   Route::get('auth/user', [\App\Http\Controllers\Api\Auth::class, 'user']);
   Route::post('auth/logout', [\App\Http\Controllers\Api\Auth::class, 'logout']);
   Route::apiResource('desserts', \App\Http\Controllers\Api\DessertController::class);
});
