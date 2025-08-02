<?php

//* controllers
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

//* models
use App\Models\RefreshToken;

//* libraries
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;

class ManageTokenController extends Controller
{
    //* generate token for app
    public function generateToken($id, $longTime = false)
    {
        $now = Carbon::now();
        $expiration = $longTime ? $now->addYear() : $now->addMinutes(15);

        $accessPayload = [
            'id' => $id,
            'jti' => (string) Str::uuid(),
            'exp' => $expiration->timestamp
        ];

        $accesToken = JWT::encode($accessPayload, env('JWT_SECRET'), 'HS256');

        $refreshTokenRaw  = Str::random(64);
        $refreshTokenHash  = hash('sha256', $refreshTokenRaw);

        RefreshToken::create([
            'user_id' => $id,
            'token' => $refreshTokenHash,
            'expires_at' => Carbon::now()->addDays(7)
        ]);

        return [
            'access_token' => $accesToken,
            'refresh_token' => $refreshTokenRaw
        ];
    }

    //* validate token from a request
    public function validateToken(Request $request)
    {
        try {
            $authorizationHeader = $request->header('Authorization');

            if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
                return [
                    'process' => 'error token',
                    'message' => 'Header Authorization inválido o no presente.',
                    'state' => false,
                    'token' => null
                ];
            }

            $tokenEncoded = substr($authorizationHeader, 7);

            $tokenDecoded = JWT::decode($tokenEncoded, new Key(env('JWT_SECRET'), 'HS256'));

            return [
                'process' => 'success token',
                'message' => 'Token válido.',
                'state' => true,
                'token' => $tokenDecoded
            ];
        } catch (\Throwable $th) {
            return [
                'process' => 'error token',
                'message' => 'Token inválido: ' . $th->getMessage(),
                'state' => false,
                'token' => null
            ];
        }
    }

    public function validateTokenAPI(Request $request)
    {
        try {
            $authorizationHeader = $request->header('Authorization');

            if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
                return [
                    'process' => 'error token',
                    'message' => 'Header Authorization inválido o no presente.',
                    'state' => false,
                    'token' => null
                ];
            }

            $tokenEncoded = substr($authorizationHeader, 7);

            $tokenDecoded = JWT::decode($tokenEncoded, new Key(env('JWT_SECRET'), 'HS256'));

            return $this->response_success([
                'process' => 'success token',
                'message' => 'Token válido.',
                'state' => true,
                'token' => $tokenDecoded
            ]);
        } catch (\Throwable $th) {
            return $this->response_error(
                'Token inválido: ' . $th->getMessage(),
                401,
                ['type' => 'tokenError']
            );
        }
    }


    public function getUserIdFromToken(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            return null;
        }

        $tokenEncoded = substr($authorizationHeader, 7);

        try {
            $tokenDecoded = JWT::decode($tokenEncoded, new Key(env('JWT_SECRET'), 'HS256'));
            return $tokenDecoded->id ?? null;
        } catch (\Throwable $th) {
            return null;
        }
    }

    //* endpoint refresh token
    public function refreshAccessToken(Request $request)
    {
        $refreshTokenRaw = $request->cookie('refresh_token');

        if (!$refreshTokenRaw) {
            return $this->response_error([
                'error' => 'Refresh token missing',
            ], 401);
        }

        $refreshTokenHash = hash('sha256', $refreshTokenRaw);

        $record = RefreshToken::where('token', $refreshTokenHash)
            ->where('revoked', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$record) {
            $tokenToInactive = RefreshToken::where('token', $refreshTokenHash)
                ->first();

            if (!$tokenToInactive) {
                return response()->json(['error' => 'Invalid or expired refresh token'], 401);
            }

            $tokenToInactive->revoked = false;
            $tokenToInactive->save();

            return response()->json(['error' => 'Invalid or expired refresh token'], 401);
        }

        //* generating new access_token
        $accessPayload = [
            'id' => $record->user_id,
            'jti' => (string) Str::uuid(),
            'exp' => Carbon::now()->addMinutes(15)->timestamp
        ];

        $newAccessToken = JWT::encode($accessPayload, env('JWT_SECRET'), 'HS256');

        return $this->response_success([
            'access_token' => $newAccessToken
        ]);
    }
}
