<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdmin\StationsService;
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
            return $this->response_success($this->stationsService->getAllStations());
        } catch (\Throwable $th) {
            Log::error('Error en StationsController@getStationsList: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine()]);
        }
    }
}
