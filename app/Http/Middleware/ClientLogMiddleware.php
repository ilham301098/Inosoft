<?php

namespace App\Http\Middleware;

use App\Models\ClientLog;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $this->inputLog($request, $response);
        return $response;
    }

    public function inputLog($request, $response) {
        # input ke database
        try {
            $model = new ClientLog();
            $payload = $model->payload($request, $response);
            $model->create($payload);
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }
}
