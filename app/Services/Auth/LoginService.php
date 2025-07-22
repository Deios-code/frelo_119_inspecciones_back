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
                'message' => 'El usuario no existe'
            ];
        }

        if (!Hash::check($request->password, $user->password)) {
            return [
                'error' => true,
                'message' => 'Credenciales incorrectas'
            ];
        }

        //* generating token
        $token = $this->manageTokenService->generateToken($user->id);

        return[
            'error' => false,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ];
    }
}
