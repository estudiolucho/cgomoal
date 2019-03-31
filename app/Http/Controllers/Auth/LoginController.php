<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    //protected $redirectPath = '/admin/credit/simulator';
    public function redirectPath()
    {
        //dd(\Auth::user()->role);
        $clase=Auth::user()->role;
        //dd($clase->id);
      //if (\Auth::user()->role == 1) {
      if ($clase->id == 1) {  
        echo 'usuario administrador';
          return '/admin/user';
      }
      elseif ($clase->id == 2) {
        echo 'usuario operador';
          return '/home';
      }
      else {
        echo 'otro usuario';
          return '/home';
      }
    }

    //If the redirect path needs custom generation logic you may define a
    //redirectTo method instead of a redirectTo property:
    /*
    protected function redirectTo()
    {
        return '/admin/credit/simulator';
    }
    */


//esto lo quite de logincontroler lebr
    //use AuthenticatesUsers;

    public function showLoginForm(){
        //echo 'estasen logincontroller';
        return view('auth.login');
    }

    public function login(Request $request)
    {
        echo 'estoy en login de  clase logincontroller ';
        //dd('voy en funcion login');
        
        //Validate the given request with the given rules.
        $this->validateLogin($request);
        echo 'hice ';

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            echo' muchos intentos de logueo ';
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        //echo' el request  contiene '.$request;
        echo 'el retorno de attemmlogin es '.$this->guard()->attempt($this->credentials($request), $request->has('remember'));
        
        /*dd(
            $this->credentials($request),
            $request->has('remember'),
            $this->guard()->attempt($this->credentials($request),$request->has('remember')),
            $this->guard()
        );*/
        // Customization: Validate if client status is active (1)        
        if ($this->attemptLogin($request)) {

            echo' attemplogin es true'.'usuario autenticado';
            return $this->sendLoginResponse($request);
        }
        
        // Customization: Validate if client status is active (1)
        $userlogin = $request->get($this->username());
        // Customization: It's assumed that uername field should be an unique field 
        $user = User::where($this->username(), $userlogin)->first();


        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);  ############ descomentar despues de las pruebas
        
         // Customization: If client status is inactive (0) return failed_status error.
        if($user){
            echo 'usuario existe';
            if ($user->active == 0) {
                echo 'usuario inactivo';
                return $this->sendFailedLoginResponse2($request, 'auth.failed_status');
            }else{
                echo 'usuario activo';
            }
        }
        //dd('voy aqui','$request->session()->regenerate() es: ', $request->session()->regenerate(), $user);

        return $this->sendFailedLoginResponse($request);
    }

//hasta aqui

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $var=$this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
        //dd($this->username(),$request,$var);
        //Validate the given request with the given rules.
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
        //echo 'estoy en validatelogin de  clase logincontroller '.$var;
    }



    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        echo 'estoy en funcion attemptLogin ';
        echo '---'.$request->has('remember');
        echo 'funcion credentials me devolvio el usuario y clave en un array ';
        echo 'y estado de la casilla recordar'; 

        /*dd(
            $this->credentials($request),
            $request->has('remember'),
            $this->guard()->attempt($this->credentials($request),$request->has('remember')),
            $this->guard()
        );*/
        return $this->guard()->attempt(
            $this->credentials($request), $request->has('remember')
        );
    }



    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        //return $request->only($this->username(), 'password');
        // Customization: validate if client status is active (1)
        $credentials = $request->only($this->username(), 'password');
        $credentials['active'] = 1;
        return $credentials;
    }



    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        echo 'estoy en sendloginresponse de  clase logincontroller';
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }



    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //
    }



    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    ///*
    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
    //*/

    // Customization: If client status is inactive (0) return failed_status error.    
    protected function sendFailedLoginResponse2(Request $request, $trans = 'auth.failed_status')
    {
        $errors = [$this->username() => trans($trans)];
        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
    



    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    /*public function username()
    {
        return 'email';
    }*/
        public function username()
    {
        return 'username';
    }



    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
    /*protected function guard()
    {
        return Auth::guard('guard-name');
    }*/
}
