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
Route::post('register', [App\Http\Controllers\API\LoginController::class, 'sign_up']);
Route::post('login', [App\Http\Controllers\API\LoginController::class, 'sign_in']);
Route::middleware('auth:api')->group(function() {
    Route::get('logout', [App\Http\Controllers\API\LoginController::class, 'logout']);
    Route::get('hobby', [App\Http\Controllers\API\LoginController::class, 'hobbylist']);
    Route::get('myprofile', [App\Http\Controllers\API\LoginController::class, 'myprofile']);
    Route::post('user_update',[App\Http\Controllers\API\LoginController::class,'update_user'])->name('update');
});