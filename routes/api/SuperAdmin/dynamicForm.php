<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DynamicFormController;

Route::group(['prefix' => 'superadmin/dynamicForm', 'middleware' => 'tokenValidator'], function () {

    //* get methods
    Route::get('findProcess', [DynamicFormController::class, 'findProcess']);
    Route::get('schema/{formId}', [DynamicFormController::class, 'getFormSchema']);        // trae el formulario completo
    Route::get('list', [DynamicFormController::class, 'listForms']);                        // listar por módulo/estado

    //* post methods
    Route::post('create', [DynamicFormController::class, 'createForm']);
    Route::post('responses/{formId}', [DynamicFormController::class, 'submitResponses']);   // enviar respuestas

});
