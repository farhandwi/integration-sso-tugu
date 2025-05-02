<?php

use App\Http\Controllers\SsoAuthController;
use App\Http\Middleware\ValidateAccessToken;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return view('test');
});

Route::get('/not-access', function () {
    return view('not-access');
});

Route::get('/token/refresh/{refresh_token}', [SsoAuthController::class, 'handleRefreshToken']);


Route::get('/dashboard', function () {
    return view('welcome');
})->middleware('validate');