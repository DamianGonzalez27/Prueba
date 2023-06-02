<?php

namespace App\Http\Middleware;

use App\Repos\SesionRepo;
use Closure;
use Illuminate\Http\Request;

class BasicMiddleware
{
    private $sesion;

    public function __construct(SesionRepo $sesion)
    {
        $this->sesion = $sesion;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $sesion = $this->sesion->findFirst(['*'], ['token' => $request->headers->get('auth')]);

        if ($sesion->usuario->membresia->nombre == "Free") {
            return response()->json([
                'message' => 'No autorizado, actualiza tu plan para acceder a esta funcionalidad.',
                'status' => 'error',
            ], 401);
        }

        return $next($request);
    }
}
