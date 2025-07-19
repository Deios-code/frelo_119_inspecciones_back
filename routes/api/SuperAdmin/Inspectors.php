<?php

use App\Http\Controllers\SuperAdmin\InspectorsController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TokenValidator;

// Route::group(['prefix' => 'superadmin/inspectors', 'middleware' =>  TokenValidator::class, 'controller' => ''], function () {
Route::group(['prefix' => 'superadmin/inspectors', 'controller' => InspectorsController::class], function () {
    Route::get('list', 'getInspectorsList');
    Route::get('edit/{id}', 'getInfoInspector');
    Route::get('create', 'prepareCreate');

    Route::post('add', 'add');

    Route::put('update/{id}', 'update');

    Route::delete('delete/{id}', 'delete');
});
