<?php

use App\Http\Controllers\Admin\InspectorsController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TokenValidator;

// Route::group(['prefix' => 'admin/inspectors', 'middleware' =>  TokenValidator::class, 'controller' => ''], function () {
Route::group(['prefix' => 'admin/inspectors', 'controller' => InspectorsController::class], function () {
    Route::get('list/{idUser}', 'getInspectorsList');
    Route::get('edit/{idInspector}/{idUser}', 'getInfoInspector');
    Route::get('create', 'prepareCreate');

    Route::post('add', 'add');

    Route::put('update/{id}', 'update');

    Route::delete('delete/{id}', 'delete');
});
