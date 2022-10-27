<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$role)
    {
        if ($request->user() && in_array($request->user()->role, $role)) {
            return $next($request);
        } else {
            $respon = [
                'msg' => 'unauthorized',
            ];
            return response()->json($respon, 401);
        }
    }
}
