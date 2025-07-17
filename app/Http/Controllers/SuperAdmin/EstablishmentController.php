<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Services\SuperAdmin\EstablishmentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class EstablishmentController extends Controller
{
    protected $establishmentService;

    public function __construct(EstablishmentService $service)
    {
        $this->establishmentService = $service;
    }

    public function getEstablishmentsList()
    {
        try {
            return $this->response_success($this->establishmentService->getEstablishmentsList());
        } catch (\Throwable $th) {
            Log::error('Error en EstablishmentController@getEstablishmentsList: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }

    public function getInfoEstablishment($id)
    {
        try {
            return $this->response_success($this->establishmentService->getInfoEstablishment($id));
        } catch (\Throwable $th) {
            Log::error('Error en EstablishmentController@getInfoEstablishment: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }
}
