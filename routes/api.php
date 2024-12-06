<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\security\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'api ready';
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});


Route::middleware('api')->group(function () {
    Route::resource('user', UserController::class);
});
