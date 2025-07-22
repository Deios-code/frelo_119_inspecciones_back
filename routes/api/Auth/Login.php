<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TokenValidator;

// Route::group(['middleware' =>  TokenValidator::class, 'controller' => ''], function () {
Route::group(['controller' => LoginController::class], function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});
