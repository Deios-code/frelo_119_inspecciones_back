<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdmin\InspectorsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class InspectorsController extends Controller
{
    protected $inspectorsService;

    public function __construct(InspectorsService $service)
    {
        $this->inspectorsService = $service;
    }

    public function getInspectorsList()
    {
        try {
            return $this->response_success($this->inspectorsService->getInspectorsList());
        } catch (\Throwable $th) {
            Log::error('Error en InspectorsController@getInspectorsList: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }

    public function getInfoInspector($id)
    {
        try {
            return $this->response_success($this->inspectorsService->getInfoInspector($id));
        } catch (\Throwable $th) {
            Log::error('Error en InspectorsController@getInfoInspector' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }
}
