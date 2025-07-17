<?php

use App\Http\Controllers\SuperAdmin\EstablishmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TokenValidator;

// Route::group(['prefix' => 'superadmin/establishment', 'middleware' =>  TokenValidator::class, 'controller' => ''], function () {
Route::group(['prefix' => 'superadmin/establishment', 'controller' => EstablishmentController::class], function () {
    Route::get('list', 'getEstablishmentsList');
    Route::get('edit/{id}', 'getInfoEstablishment');
});
