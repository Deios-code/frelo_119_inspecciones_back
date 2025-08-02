<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ManageTokenController;
use Illuminate\Support\Facades\Route;

// Route::group(['middleware' =>  TokenValidator::class, 'controller' => ''], function () {
Route::group(['controller' => AuthController::class], function () {
    
    Route::post('login', 'login');

    Route::group(['prefix' => 'password'], function () {
        Route::post('reset', 'resetPassword');
        Route::post('change', 'changePassword');
    });

    Route::group(['prefix' => 'register'], function () {
        Route::get('get-departments', 'getDepartmentsList');
        Route::get('get-cities/{departmentId}', 'getCitiesList');

        Route::post('/', 'register');
    });

    Route::get('/auth/refresh',  [ManageTokenController::class, 'refreshAccessToken']);
    Route::get('/auth/validateToken',  [ManageTokenController::class, 'validateTokenAPI']);
});
