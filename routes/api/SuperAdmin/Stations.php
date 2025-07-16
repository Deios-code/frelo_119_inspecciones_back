<?php

use App\Http\Controllers\SuperAdmin\StationsController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TokenValidator;

// Route::group(['prefix' => '', 'middleware' =>  TokenValidator::class, 'controller' => ''], function () {
Route::group(['prefix' => 'superadmin/stations', 'controller' => StationsController::class], function () {
    Route::get('browse', 'getStationsList');
    Route::get('read/{id}', 'index');

    Route::post('add', 'addStation');

    Route::put('edit/{id}', 'editStation');
    Route::delete('delete/{id}', 'deleteStation');
});
