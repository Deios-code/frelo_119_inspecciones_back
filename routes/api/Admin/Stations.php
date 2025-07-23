<?php

use App\Http\Controllers\Admin\StationsController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TokenValidator;

// Route::group(['prefix' => 'admin/stations', 'middleware' =>  TokenValidator::class, 'controller' => StationsController::class], function () {
Route::group(['prefix' => 'admin/stations', 'controller' => StationsController::class], function () {
    Route::get('list/{idUser}', 'getStationsList');
    Route::get('edit/{id}/{idUser}', 'getInfoStation');

    Route::put('update/{id}', 'updateStation');
});
