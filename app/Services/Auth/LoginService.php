<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\LoginRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    protected $loginRepository;

    public function __construct(LoginRepositoryInterface $repository)
    {
        $this->loginRepository = $repository;
    }

    public function login($request)
    {
        $user = $this->loginRepository->validateMail($request->email);

        if (!$user) {
            return [
                'error' => true,
                'msg' => 'Datos no válidos, por favor corregir'
            ];
        }

        if (!Hash::check($request->password, $user->us_password)) {
            return [
                'error' => true,
                'msg' => 'Credenciales incorrectas'
            ];
        }


        return [
            'error' => false,
            'msg' => 'Login successful',
            'data' => [
                'user' => [
                    'code' => $user->id,
                    'name' => $user->us_name . ' ' . $user->us_lastname,
                    'email' => $user->us_email,
                    'role' => $user->us_role
                ]
            ]
        ];
    }


    public function getUserInfo($userId)
    {
        $user = $this->loginRepository->getUserInfo($userId);

        if (!$user) {
            return [
                'error' => true,
                'message' => 'User not found'
            ];
        }

        return [
            'error' => false,
            'message' => 'User found',
            'data' => [
                'code' => $user->id,
                'name' => $user->us_name . ' ' . $user->us_last_name,
                'email' => $user->us_email,
                'role' => $user->us_role
            ]
        ];
    }
}
