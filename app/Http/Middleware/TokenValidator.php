<?php

//* namespace
namespace App\Http\Middleware;

//* controllers
use App\Http\Controllers\ManageTokenController;

//* libraries
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $tokenValidator = new ManageTokenController();

        $tokenProcess = $tokenValidator->validateToken($request);

        if (!$tokenProcess['state']) {
            return $tokenValidator->response_error($tokenProcess['message']);
        }

        return $next($request);
    }

    
}
