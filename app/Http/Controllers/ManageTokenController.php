<?php

//* controllers
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

//* libraries
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ManageTokenController extends Controller
{

    //* generate token for postman
    public function generateTokenPostman(Request $request)
    {
        $id = $request->route('userID');

        if ($id == '') {
            return $this->response_success('id no puede estar vacio');
        }

        $token = JWT::encode(['id' => $id], env('APP_KEY'), 'HS256');
        return $this->response_success($token);
    }

    //* test a token for postman
    public function testTokenPostman(Request $request)
    {
        try {
            $jwt = substr($request->header('Authorization'), 7);

            $decoded = JWT::decode($jwt, new Key(env('APP_KEY'), 'HS256'));

            return $this->response_success([
                'token_decoded' => $decoded,
                'process' => 'true',
            ]);

        } catch (\Throwable $th) {
            return $this->response_error([
                'error' => 'Token is not valid'
            ]);
        }
    }


    //* generate token for app
    public function generateToken($id)
    {
        $token = JWT::encode(['id' => $id], env('APP_KEY'), 'HS256');
        return $token;
    }


    //* validate token from a request
    public function validateToken(Request $request)
    {
        try {
            $tokenEncoded = substr($request->header('Authorization', 'Bearer <token>'), 6);
            $tokenDecoded = JWT::decode($tokenEncoded, new Key(env('APP_KEY'), 'HS256'));

            return [
                'process' => 'success token',
                'message' => 'token valido',
                'state' => true,
                'token' => $tokenDecoded
            ];

        } catch (\Throwable $th) {
            return [
                'process' => 'error token',
                'message' => 'token invalido',
                'state' => false,
                'token' => ''
            ];
        }
    }
}
