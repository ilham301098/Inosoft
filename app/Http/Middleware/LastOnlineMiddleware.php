<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LastOnlineMiddleware
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
        if (auth()->guest()) {
            return $next($request);    
        }
        
        $lastOnline = Carbon::parse(auth()->user()->last_online_at);
        $minuteDiff = Carbon::now()->diffInMinutes($lastOnline);
        if ($minuteDiff >= 5) {
            DB::table('users')->where('id', auth()->user()->id)->update(['last_online_at' => now()]);
        }
        return $next($request);
    }
}
