<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\LoginService;
use App\Services\Auth\RegisterService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    protected $loginService;

    public function __construct(LoginService $service)
    {
        $this->loginService = $service;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->response_error('Datos no válidos, por favor corregir: ' . $validator->errors(), 500);
        }

        try {
            $response = $this->loginService->login($request);

            if ($response['error']) {
                return $this->response_error($response['msg'], 500);
            }

            return $this->response_success($response['data']);
        } catch (\Throwable $th) {
            Log::error('Error en LoginController@login: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }

    public function getDepartmentsList(RegisterService $registerService)
    {
        try {
            return $this->response_success($registerService->getDepartmentsList());
        } catch (\Throwable $th) {
            Log::error('Error en authController@getDepartmentsList: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }

    public function getCitiesList(RegisterService $registerService, $departmentId)
    {
        try {
            return $this->response_success($registerService->getCitiesList($departmentId));
        } catch (\Throwable $th) {
            Log::error('Error en authController@getCitiesList: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }

    public function register(Request $request, RegisterService $registerService)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,us_email',
            'city' => 'required|exists:cities,id',
            'habeas_data' => 'required|boolean|accepted',
        ]);
        if ($validator->fails()) {
            return $this->response_error('Datos no válidos, por favor corregir: ' . $validator->errors(), 500);
        }

        try {
            $response = $registerService->register($request->all());

            if ($response['error']) {
                return $this->response_error($response['msg'], 500);
            }

            return $this->response_success($response['data']);
        } catch (\Throwable $th) {
            Log::error('Error en authController@register: ' . $th->getMessage());
            return $this->response_error([$th->getMessage(), $th->getLine(), $th->getFile()]);
        }
    }
}
