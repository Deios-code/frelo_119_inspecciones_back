<?php

use App\Http\Controllers\Admin\EstablishmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TokenValidator;

// Route::group(['prefix' => 'admin/establishment', 'middleware' =>  TokenValidator::class, 'controller' => ''], function () {
Route::group(['prefix' => 'admin/establishment', 'controller' => EstablishmentController::class], function () {
    Route::get('list/{idUser}', 'getEstablishmentsList');
    Route::get('edit/{id}/{idUser}', 'getInfoEstablishment');
});
