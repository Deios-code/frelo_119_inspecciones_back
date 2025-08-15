<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DynamicFormController;

Route::group(['prefix' => 'superadmin/dynamicForm', 'middleware' => 'tokenValidator'], function () {

    //* get methods
    Route::get('findProcess', [DynamicFormController::class, 'findProcess']);

    //* post methods
    Route::post('create', [DynamicFormController::class, 'createForm']);
});
