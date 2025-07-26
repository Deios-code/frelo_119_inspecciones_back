<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\EstablishmentService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class EstablishmentController extends Controller
{
    protected $establishmentService;

    public function __construct(EstablishmentService $service)
    {
        $this->establishmentService = $service;
    }

    public function getEstablishmentsList(Request $request)
    {
        try {
            return $this->response_success($this->establishmentService->getEstablishmentsList($request));
        } catch (\Throwable $th) {
            Log::error('Error en UserEstablishmentController@getEstablishmentsList: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }

    public function getInfoEstablishment(Request $request, $id)
    {
        try {
            return $this->response_success($this->establishmentService->getInfoEstablishment($request, $id));
        } catch (\Throwable $th) {
            Log::error('Error en UserEstablishmentController@getInfoEstablishment: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }

    public function addEstablishment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_establishment' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'nit' => 'required|string|max:50|unique:establishments,es_nit',
            'email' => 'required|string|max:250|unique:establishments,es_email_establishment',
            'commune' => 'required|string|max:100',
            'exoneration' => 'required|accepted',
            'neighborhood' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return $this->response_error($validator->errors());
        }

        try {
            $response = $this->establishmentService->addEstablishment($request);
            if ($response['error']) {
                return $this->response_error($response['message']);
            }
            return $this->response_success($response['data']);
        } catch (\Throwable $th) {
            Log::error('Error en UserEstablishmentController@addEstablishment: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }


    public function updateEstablishment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|exists:establishments,id',
            'name_establishment' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'nit' => 'required|string|max:50|unique:establishments,es_nit,' . $request->input('code'),
            'email' => 'required|string|max:250|unique:establishments,es_email_establishment,' . $request->input('code'),
            'commune' => 'required|string|max:100',
            'exoneration' => 'required|accepted',
            'neighborhood' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return $this->response_error($validator->errors());
        }

        try {
            $response = $this->establishmentService->updateEstablishment($request);
            if ($response['error']) {
                return $this->response_error($response['message']);
            }
            return $this->response_success($response['data']);
        } catch (\Throwable $th) {
            Log::error('Error en UserEstablishmentController@updateEstablishment: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }

    public function getInfoAdditionalUser(Request $request)
    {
        try {
            $response = $this->establishmentService->getInfoAdditionalUser($request);
            if ($response['error']) {
                return $this->response_error($response['message']);
            }
            return $this->response_success($response['data']);
        } catch (\Throwable $th) {
            Log::error('Error en UserEstablishmentController@getInfoAdditionalUser: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }

    public function updateInfoAdditionalUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_document' => 'required|string|in:CEDULA,CEDULA_EXTRANJERIA,PASAPORTE',
            'document' => 'required|integer',
            'birthday' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->response_error($validator->errors());
        }

        try {
            $response = $this->establishmentService->updateInfoAdditionalUser($request);
            if ($response['error']) {
                return $this->response_error($response['message']);
            }
            return $this->response_success($response['data']);
        } catch (\Throwable $th) {
            Log::error('Error en UserEstablishmentController@updateInfoAdditionalUser: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }
}
