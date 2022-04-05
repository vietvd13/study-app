<?php

use App\Http\Controllers\Api\SpeechController;
use App\Http\Controllers\Api\AuthController;
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
Route::namespace('Api')->group(function() {
    Route::post('/auth/login','AuthController@login');
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/auth/user','AuthController@user');
        Route::apiResource('/users', 'UserController');
        Route::get('/roles', 'UserController@roles');
    });
});
