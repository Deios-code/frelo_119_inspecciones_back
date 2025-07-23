<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\LoginRepositoryInterface;
use App\Services\ManageToken\ManageTokenService;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    protected $loginRepository;
    private $manageTokenService;

    public function __construct(LoginRepositoryInterface $repository, ManageTokenService $manageTokenService)
    {
        $this->manageTokenService = $manageTokenService;
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

        //* generating token
        $token = $this->manageTokenService->generateToken($user->id);

        return[
            'error' => false,
            'msg' => 'Login successful',
            'data' => [
                'user' => [
                    'code' => $user->id,
                    'name' => $user->us_name. ' ' . $user->us_lastname,
                    'email' => $user->us_email,
                    'role' => $user->us_role
                ],
                'access_token' => $token
            ]
        ];
    }
}
