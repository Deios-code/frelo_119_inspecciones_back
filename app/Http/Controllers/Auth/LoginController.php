<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\LoginService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class LoginController extends Controller
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
}
