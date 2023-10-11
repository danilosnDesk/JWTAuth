<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class ApiProtectedRoutes extends BaseMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try {
             $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $excep) {
            
            if ($excep instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json( [ 'status' => 'Token expirado' ], 401 );
            } else if($excep instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json( [ 'status' => 'Token Inválido' ], 401 );
            }else {
                return response()->json( [ 'status' => 'Token not found' ], 401 );
            }
            
        }
        return $next($request);
    }
}
