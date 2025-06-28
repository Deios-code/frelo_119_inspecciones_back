<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function response_success($data = [], $status = 200, $message = 'The request has succeeded.')
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public function response_error($message = '.', $status = 400)
    {
        $response = [
            'status' => 'error',
            'message' => $message,
            'data' => []
        ];

        if (env('APP_DEBUG') == false) {
            return response()->json([
                'response' => 'Internal error, please try again'
            ], $status);
        }

        return response()->json($response, $status);
    }
}
