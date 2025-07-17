<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

use App\Services\SuperAdmin\StationsService;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class StationsController extends Controller
{

    protected $stationsService;

    public function __construct(StationsService $service)
    {
        $this->stationsService = $service;
    }

    public function getStationsList()
    {
        try {
            return $this->response_success($this->stationsService->getStationsList());
        } catch (\Throwable $th) {
            Log::error('Error en StationsControllerSuperAdmin@getStationsList: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine()]);
        }
    }

    public function getInfoStation($id)
    {
        try {
            return $this->response_success($this->stationsService->getInfoStation($id));
        } catch (\Throwable $th) {
            Log::error('Error en StationsControllerSuperAdmin@getInfoStation: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine()]);
        }
    }

    public function prepareCreateStation()
    {
        try {
            return $this->response_success($this->stationsService->prepareCreateStation());
        } catch (\Throwable $th) {
            Log::error('Error en StationsControllerSuperAdmin@prepareCreateStation: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine()]);
        }
    }

    public function addStation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nit' => 'required|string|max:255|unique:stations,st_nit',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:stations,st_phone',
            'longitude' => 'string|nullable',
            'latitude' => 'string|nullable',
            'codeUserAsigned' => 'nullable|exists:users,id',
            'codeCity' => 'required|exists:cities,id'
        ]);

        if ($validator->fails()) {
            return $this->response_error('Datos no válidos, por favor corregir: ' . $validator->errors(), 500);
        }

        try {
            return $this->response_success($this->stationsService->addStation($request->all()));
        } catch (\Throwable $th) {
            Log::error('Error en StationsControllerSuperAdmin@addStation: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine()]);
        }
    }

    public function updateStation($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|integer|exists:stations,id',
            'longitude' => 'string|nullable|unique:stations,st_longitude,' . $id,
            'latitude' => 'string|nullable|unique:stations,st_latitude,' . $id,
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'nit' => 'required|string|max:255|unique:stations,st_nit,' . $id,
            'phone' => 'required|string|max:20|unique:stations,st_phone,' . $id,
            'codeUserAsigned' => 'required|exists:users,id',
            'codeCity' => 'required|exists:cities,id'
        ]);

        if ($validator->fails()) {
            return $this->response_error('Datos no válidos, por favor corregir: ' . $validator->errors(), 500);
        }

        try {
            return $this->response_success($this->stationsService->updateStation($id, request()->all()));
        } catch (\Throwable $th) {
            Log::error('Error en StationsControllerSuperAdmin@updateStation: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine()]);
        }
    }

    public function deleteStation($id)
    {
        try {
            return $this->response_success($this->stationsService->deleteStation($id));
        } catch (\Throwable $th) {
            Log::error('Error en StationsControllerSuperAdmin@deleteStation: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine()]);
        }
    }
}
