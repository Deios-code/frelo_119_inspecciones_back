<?php

use App\Http\Controllers\SuperAdmin\StationsController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TokenValidator;

// Route::group(['prefix' => '', 'middleware' =>  TokenValidator::class, 'controller' => ''], function () {
Route::group(['prefix' => 'superadmin/stations', 'controller' => StationsController::class], function () {
    Route::get('list', 'getStationsList');
    Route::get('edit/{id}', 'getInfoStation');
    Route::get('create', 'prepareCreateStation');

    Route::post('add', 'addStation');

    Route::put('update/{id}', 'updateStation');
    Route::delete('delete/{id}', 'deleteStation');
});
