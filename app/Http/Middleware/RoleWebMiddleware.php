<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Contracts\Auth\Factory as Auth;

class RoleWebMiddleware
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$id_menu){
        /*
        Super Admin:

        User:

        */

        if ($this->auth->guest()) {
            abort('401');
        }

        if(auth()->user()->role=='superadmin'){
            //Super Admin
            //ALL MENU ALLOWED
        }
        if(auth()->user()->role!='superadmin'){
            $role_name=auth()->user()->role;

            $role=DB::table('role')->where('name',$role_name)->first();
            $access=DB::table('role_menu')->select('id_menu')->where('id_role',$role->id)->get();
            
            $accessList=[];
            foreach ($access as $key => $value) {
                $accessList[]=$access[$key]->id_menu;
            }            

            if(!in_array($id_menu,$accessList)){
                abort('403');
            }
        }

        return $next($request);
    }
}

