<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;



class lebrLoginController_lebr extends Controller
{
        //use AuthenticatesUsers;

    public function showLoginForm(){
        //echo 'estasen logincontroller lebr';
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function login(Request $request)
    {
        echo 'estoy en login de  clase logincontrollerlebr ';
        
        
        $username=$request->username;
        $password=$request->password;
        echo $request;
        $m= Auth();
        
        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            // Authentication passed...
            echo 'usuario autenticadoooooooo';

            return redirect()->intended('dashboard');
        }else{
            echo 'no entro';
        }
        //dd($m);
    }
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}