<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Exceptions\InvalidTokenException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware 
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $request['user'] = $user;
        } catch (JWTException $e) {
            throw new InvalidTokenException('Unauthorized');
        }

        return $next($request);
    }
}