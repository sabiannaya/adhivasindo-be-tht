<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\DataController;

Route::prefix('auth')->controller(UserController::class)->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::get('me', 'me');
        Route::put('refresh', 'refresh');
        Route::put('update', 'update');
        Route::delete('logout', 'logout');
    });

    Route::post('login', 'login');
    Route::post('register', 'register');
});

Route::prefix('data')->controller(DataController::class)->group(function () {
    Route::get('search', 'search');
});
