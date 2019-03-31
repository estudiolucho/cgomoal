<?php

namespace App\Http\Middleware;

use Closure;
//use Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Factory as Auth;

class Admin
{
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
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
    public function handle($request, Closure $next)
    {
        if($this->auth->user()->isAdmin()){;
            return $next($request);
        }elseif($this->auth->user()->isOperator()){
            return $next($request);
        }else{
            //dd('no tiene permisos para acceder a la administracion');
            abort(503);
            //return view ('');
        }
    }

}
