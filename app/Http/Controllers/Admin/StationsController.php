<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Services\Admin\StationsService;

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

    public function getStationsList($idUser)
    {
        try {
            return $this->response_success($this->stationsService->getStationsList($idUser));
        } catch (\Throwable $th) {
            Log::error('Error en StationsControllerAdmin@getStationsList: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine()]);
        }
    }

    public function getInfoStation($id, $idUser)
    {
        try {
            return $this->response_success($this->stationsService->getInfoStation($id, $idUser));
        } catch (\Throwable $th) {
            Log::error('Error en StationsControllerAdmin@getInfoStation: ' . $th->getMessage());
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
            'phone' => 'required|string|max:20|unique:stations,st_phone,' . $id
        ]);

        if ($validator->fails()) {
            return $this->response_error('Datos no válidos, por favor corregir: ' . $validator->errors(), 500);
        }

        try {
            return $this->response_success($this->stationsService->updateStation($id, request()->all()));
        } catch (\Throwable $th) {
            Log::error('Error en StationsControllerAdmin@updateStation: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine()]);
        }
    }
}
