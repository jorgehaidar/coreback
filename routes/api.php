<?php

use App\Http\Controllers\security\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'api ready';
});


Route::middleware('api')->group(function () {
    Route::resource('user', UserController::class);
});
