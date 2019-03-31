{{--   dd($user,$role,'crear formulario para editar usuarios'	)--}}
pendiente: que pase la seleccion inactivo al request <br>
que los select se muestren con lo que haya en el registro y al pasar al request tome lo selecionado
@extends('admin/template/main')
@section('title', 'Usuarios')
@section('entidad') 
 	Modificar Usuario
@endsection
@section('boton')
	<p align="right">
 	<a href="{{ route('user.index') }}" class="btn btn-primary">Ir a Usuarios</a>
 	</p>
@endsection
@section('contenido')
	{!! Form::open(['route' => ['user.update',$user], 'method'=>'PUT']) !!}

 	<table  width="800" >
 		<td>
 			<table  width="320" >
 				<tr>
 					<td>{!! Form::label('document','Documento #') !!}</td>
 					<td>{!! Form::input('text','document', $user->document,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'num cedula','size'=>'8')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('name','Nombres') !!}</td>
 					<td>{!! Form::input('text','name', $user->name,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'nombres','size'=>'15')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('lastname','Apellidos') !!}</td>
 					<td>{!! Form::input('text','lastname', $user->lastname,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'apellidos','size'=>'20')) !!}</td>
 				</tr>
 				@if(Auth::user()->isAdmin())
 				<tr>
 					<td>{!! Form::label('username','Nombre de Usuario') !!}</td>
 					<td>{!! Form::input('text','username', $user->username,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'login','size'=>'15','readonly')) !!}</td>
 				</tr>
 				@endif
 				<td>{!! Form::text('password',$user->password,['hidden']) !!}</td>

 				

 				{{--  oculto campo password para que solo se cambie por otro lado 
 				<tr>
 					<td>{!! Form::label('password','Contrasena',['hidden']) !!}</td>
 					<td>{!! Form::password('password',array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'clave de acceso','size'=>'15','hidden')) !!}</td>
 				</tr>
 				--}}


 				
 				<tr>
 					<td>{!! Form::label('password','Contrasena') !!}</td>
 					<td>{!! Form::password('password',array('autofocus' => 'autofocus', 'placeholder' => 'clave de acceso','size'=>'15')) !!}</td>
 				</tr>
 				@if(Auth::user()->isAdmin())
 				<tr>
 					<td>{!! Form::label('user_id','Perfil de acceso') !!}</td>
					<td>{!! Form::input('text','role_id',$user->role_id,['size'=>'1']) !!}</td>
				</tr>
				@endif
				 <tr>
 					<td>{!! Form::label('user_id','Tipo de Usuario') !!}</td>
					<td>{!! Form::input('text','type',$user->type,['size'=>'10','placeholder' => 'cliente o socio']) !!}</td>
				</tr>

 				
{{--  
 				<tr>
 					<td>{!! Form::label('user_id','Perfil de acceso') !!}</td>
					<td>{!! Form::select('role_id',array('null' =>'Seleccione') + array_pluck($role,'name','id'),['required'=>'required']) !!}</td>
				</tr>
 				<tr>
 					<td>{!! Form::label('user_id','Tipo Usuario') !!}</td>
					<td>{!! Form::select('type', array('null'=>'Seleccione','cliente'=>'Cliente','socio' =>'Socio'), ['required'=>'required']) !!}</td>
 					
 				</tr>
--}}
 			</table>
 		</td>
 		<td width="20"></td>
 		<td>
 			<table  >
 				<tr>
 					<td>{!! Form::label('email','Correo') !!}</td>
 					<td>{!! Form::input('text','email', $user->email,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Correo Electronico','size'=>'40')) !!}</td>
 				</tr>
 				<tr>
 					<td width=40%>{!! Form::label('address','Direccion Principal') !!}</td>
 					<td width=70%>{!! Form::input('text','main_addr', $user->main_addr,array('required' => 'required','autofocus' => 'autofocus', 'placeholder' => 'direccion de domicilio','size'=>'40')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('main_phone','Telefono ') !!}</td>
 					<td>{!! Form::input('text','main_phone', $user->main_phone,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Telefono','size'=>'15')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('secondary_addr','Direccion Alternativa') !!}</td>
 					<td>{!! Form::input('text','secondary_addr', 'la segundacasa',array('autofocus' => 'autofocus', 'placeholder' => 'Direccion 2','size'=>'40')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('main_phone','Telefono 2 ') !!}</td>
 					<td>{!! Form::input('text','secondary_phone', $user->secondary_phone,array('autofocus' => 'autofocus', 'placeholder' => 'Telefono2','size'=>'15')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('referer','Referidor') !!}</td>
 					<td>{!! Form::input('text','referrer', $user->referrer,array('autofocus' => 'autofocus', 'placeholder' => 'Referido por','size'=>'15')) !!}</td>
 				</tr>
 				
 				<tr>
 					<td>{!! Form::label('state','Estado') !!}</td>
 					<td>{!! Form::checkbox('activ',($user->active==1?true:null),($user->active==1?true:0)) !!}</td>
 					
 				</tr>
 			</table>
 		</td>
 	</table>
 @endsection

 @section('contenido2')
 	<div class="form-group">
 		{!! Form::submit('Editar',['class'=> 'btn btn-primary']) !!}
 	</div>
 	<p class="navbar-text">Todos los derechos Reservados &copy {{ date('Y') }}</p>
 	<hr>
	<p class="navbar-text navbar-right">Creado: {{ $user->created_at }}| Modificado: {{$user->updated_at}}</p>

 	{!! Form::close() !!}
@endsection