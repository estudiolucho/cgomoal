<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Credit;
use App\Payment;
use DateTime;

class UserController extends Controller
{
    
    public function index(){
    	$users= User::orderBy('id','DESC')->paginate(15);
    	//dd('fdsfsdf');
    	return view('admin.users.index')
    		->with('users',$users);
    }
    public function show(){

    }
    public function list(){
        $users= User::all();
        return view('admin.users.list')->with('users',$users);
    }


    public function find(Request $request){
        $user = User::where('document','=', $request->document)->first();
        $users= User::orderBy('id','DESC');
        dd($request,$user,$users);
        return view('admin.users.index')
            ->with('users',$user);
    }
    private function calIntMes($mes,$tasa,$salcapital){
        $int=$mes*($tasa/100)*$salcapital;
        return($int);
    }




    public function edit($id){
        //dd($id);
        $user= User::find($id);
        $role = Role::all('id','name');
        return view('admin.users.edit')
            ->with('role',$role)->with('user',$user);
    }
    /**
     *Update the specified resource in storage
     *
     *@param \Illuminate\Http\Request $request
     *@param int $id
     *@return \Illuminate\Http\Response
     */
    public function update(Request $request,$id){
        $user = User::find($id);
        //echo 'activo:    '.$request->active.'  ';//dd($request);
        //echo 'pass ingresado '.$request->password;

        //si $request->password es null, el $hashed es el de $user->password;
        if ($request->password === null ){
            $hashed = $user->password;
        }else{
            $hashed = Hash::make($request->password , [
                'rounds' => 12
            ]);
        }
        //verifica si el usuario modificado es tipo admin
        if($user->role_id == 1|| $request->role_id==1){
            //valida si el usuario que esta creando sea admin
            if(!Auth::user()->isAdmin()){
                $text='No tiene permiso para Modificar al Administrador.';
                return view('admin.template.messages')->with('text',$text);
            }
        }
        if(!($request->type == 'cliente'|| $request->type == 'socio') ){
            //valida si el usuario que esta creando sea admin
                $text='Tipo de  Usuario incorrecto. Use socio ó cliente';
                return view('admin.template.messages')->with('text',$text);
        }
        $user->fill($request->all());
        $user->password=$hashed;
        $now = new DateTime();
        $user->updated_at = $now;
        //dd('el user es:',$user);
        $user->user_update=Auth::user()->username;
        $user->save();
        return redirect()->route('user.index');
    }


    public function create(){
    	//$user = User::all('id','document','active')->where('active','1');
    	$role = Role::all('id','name');
    	return view('admin.users.create')->with('roles',$role);
    }
    public function store(UserRequest $request){
        //validar que el usuario y cedula no exista en la bd
        $userexist=User::where('document','=',$request->document)->first();
        if ($userexist){
            $text='El  Documento '.$request->document.' ya existe.';
            return view('admin.template.messages')->with('text',$text);
        }else{
            //valida que el nombre de usuraio no exista en la bd
            $userexist=User::where('username','=',$request->username)->first();
            if($userexist){
                $text='El nombre de usuario '.$request->username.' ya existe';
                return view('admin.template.messages')->with('text',$text);
            }else{
            $user= new User($request->all());
            //verifica si el usuario a crear es tipo admin
            if($user->role_id == 1){
                //valida si el usuario que esta creando sea admin
                if(!Auth::user()->isAdmin()){
                    $text='No tiene permiso para crear usuario Tipo Administrador.';
                    return view('admin.template.messages')->with('text',$text);
                }
            }
            $hashed = Hash::make($request->password , ['rounds' => 12]);
            $user->password=$hashed;
            $user->user_create=Auth::user()->username;
            $user->save();
            //dd('Usuario creado');
            return $this->index();
            }
        }
    }

}
