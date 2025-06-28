<?php

namespace App\Http\Controllers;

//* controllers
use App\Http\Controllers\Controller;

//* services
use App\Services\Mail\SendMailService;

//* libraries
use Illuminate\Http\Request;

class SendMailController extends Controller
{
    public function testMail(Request $request, SendMailService $mailService)
    {
        try {
            return $this->response_success($mailService->testMail($request));
        } catch (\Throwable $th) {
            return $this->response_error([
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);
        }
    }
}
