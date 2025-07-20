<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Services\Admin\InspectorsService;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class InspectorsController extends Controller
{
    protected $inspectorsService;

    public function __construct(InspectorsService $service)
    {
        $this->inspectorsService = $service;
    }

    public function getInspectorsList($idUser)
    {
        try {
            return $this->response_success($this->inspectorsService->getInspectorsList($idUser));
        } catch (\Throwable $th) {
            Log::error('Error en InspectorsController@getInspectorsList: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }

    public function getInfoInspector($idInspector, $idUser)
    {
        try {
            return $this->response_success($this->inspectorsService->getInfoInspector($idInspector, $idUser));
        } catch (\Throwable $th) {
            Log::error('Error en InspectorsController@getInfoInspector' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }

    public function prepareCreateInspector($idUser)
    {
        try {
            return $this->response_success($this->inspectorsService->prepareCreateInspector($idUser));
        } catch (\Throwable $th) {
            Log::error('Error en InspectorsController@prepareCreateInspector: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }

    public function addInspector(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|integer',
            'email' => 'required|string|max:255',
            'type_document' => 'required|string|in:CEDULA,CEDULA_EXTRANJERIA,PASAPORTE',
            'document' => 'required|integer',
            'birthday' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'station_code' => 'required|integer|exists:stations,id',
            'city_code' => 'required|integer|exists:cities,id',
            'user_station' => 'required|integer|exists:stations,st_user_id',
            'range' => 'required|string|in:CAPITAN,TENIENTE,SUBTENIENTE,SARGENTO,CABO,BOMBERO,INSPECTOR',
            'department_code' => 'required|integer|exists:departments,id'
        ]);

        if ($validator->fails()) {
            return $this->response_error('Datos no válidos, por favor corregir: ' . $validator->errors(), 500);
        }

        try {
            $response = $this->inspectorsService->addInspector($request);
            if ($response['error']) {
                return $this->response_error($response['msg'], 500);
            }
            return $this->response_success($request->all());
        } catch (\Throwable $th) {
            Log::error('Error en InspectorsController@addInspector: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }

    public function updateInspector(Request $request, $idUser)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|integer|exists:users,id',
            'code_inspector' => 'required|integer|exists:inspectors,id',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|integer',
            'email' => 'required|string|max:255',
            'type_document' => 'required|string|in:CEDULA,CEDULA_EXTRANJERIA,PASAPORTE',
            'document' => 'required|integer',
            'birthday' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'station_code' => 'required|integer|exists:stations,id',
            // 'city_code' => 'required|integer|exists:cities,id',
            'user_station' => 'required|integer|exists:stations,st_user_id',
            'range' => 'required|string|in:CAPITAN,TENIENTE,SUBTENIENTE,SARGENTO,CABO,BOMBERO,INSPECTOR',
            // 'department_code' => 'required|integer|exists:departments,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->response_error('Datos no válidos, por favor corregir: ' . $validator->errors(), 500);
        }

        try {
            $response = $this->inspectorsService->updateInspector($request, $idUser);
            if ($response['error']) {
                return $this->response_error($response['msg'], 500);
            }
            return $this->response_success($request->all());
        } catch (\Throwable $th) {
            Log::error('Error en InspectorsController@updateInspector: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }
}
