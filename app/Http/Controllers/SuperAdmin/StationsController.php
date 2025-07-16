<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class StationsController extends Controller
{
    public function getStationsList($request)
    {
        try {
            return $this->response_success('');
        } catch (\Throwable $th) {
            Log::error('Error en StationsController@getStationsList: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine()]);
        }
    }
}
