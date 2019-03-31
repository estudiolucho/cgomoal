<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Factory as Auth;


class Operator
{
    protected $auth;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
    public function handle($request, Closure $next)
    {
        if($this->auth->user()->isOperator()){;
            return $next($request);
        }else{
            //dd('no tiene permisos para acceder a la administracion');
            abort(503);
            //return view ('');
        }
    }
}
