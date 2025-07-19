<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\InspectorsService;
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
}
