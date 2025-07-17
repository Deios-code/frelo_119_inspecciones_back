<?php

use App\Http\Controllers\Admin\StationsController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TokenValidator;

// Route::group(['prefix' => '', 'middleware' =>  TokenValidator::class, 'controller' => ''], function () {
Route::group(['prefix' => 'admin/stations', 'controller' => StationsController::class], function () {
    Route::get('list/{idUser}', 'getStationsList');
    Route::get('edit/{id}/{idUser}', 'getInfoStation');

    Route::put('update/{id}', 'updateStation');
});
