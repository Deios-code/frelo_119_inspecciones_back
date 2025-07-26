<?php

use App\Http\Controllers\User\EstablishmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TokenValidator;

Route::group(['prefix' => 'user/establishment', 'middleware' =>  TokenValidator::class, 'controller' => EstablishmentController::class], function () {
    Route::get('list', 'getEstablishmentsList');
    Route::get('edit/{id}', 'getInfoEstablishment');
    Route::get('get-info-additional-user', 'getInfoAdditionalUser');

    Route::post('add', 'addEstablishment'); //TODO: Ya guarda los campos de la BD pero faltan los campos de los adjuntos como logo, cc, camara y comercio, etc...

    Route::put('update/{id}', 'updateEstablishment');
    Route::put('update-info-additional-user', 'updateInfoAdditionalUser');

    // Route::delete('delete/{id}', 'deleteEstablishment');
});
