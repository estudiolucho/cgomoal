@extends('admin/template/main')
@section('title', 'Creditos')
@section('entidad') 
 	Crear Usuario
@endsection
@section('boton')
	<p align="right">
 	<a href="{{ route('user.index') }}" class="btn btn-info">Volver</a>
 	</p>
@endsection
@section('contenido')
	{!! Form::open(['route' => 'user.store', 'method'=>'POST']) !!}
 	<table  width="800" >
 		<td>
 			<table  width="320" >
 				<tr>
 					<td>{!! Form::label('document','Documento #') !!}</td>
 					<td>{!! Form::input('text','document',null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'num cedula','size'=>'8')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('name','Nombres') !!}</td>
 					<td>{!! Form::input('text','name',null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'nombres','size'=>'20')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('lastname','Apellidos') !!}</td>
 					<td>{!! Form::input('text','lastname', null,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'apellidos','size'=>'20')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('username','Nombre de Usuario') !!}</td>
 					<td>{!! Form::input('text','username', null,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'login','size'=>'15')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('password','Contrasena') !!}</td>
 					<td>{!! Form::password('password',array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'clave de acceso','size'=>'15')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('user_id','Perfil de acceso') !!}</td>
					<td>{!! Form::select('role_id',array(null =>'Seleccione') + array_pluck($roles,'name','id'),['required'=>'required']) !!}</td>
 					
 				</tr>
 				<tr>
 					<td>{!! Form::label('user_id','Tipo Usuario') !!}</td>
					<td>{!! Form::select('type',array(null =>'Seleccione','cliente'=>'Cliente','socio' =>'Socio'),null,['required'=>'required']) !!}</td>
 				</tr>
 			</table>
 		</td>
 		<td width="20"></td>
 		<td>
 			<table  >
 				<tr>
 					<td>{!! Form::label('email','Correo') !!}</td>
 					<td>{!! Form::input('text','email', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Correo Electronico','size'=>'40')) !!}</td>
 				</tr>
 				<tr>
 					<td width=40%>{!! Form::label('address','Direccion Principal') !!}</td>
 					<td width=70%>{!! Form::input('text','main_addr', null,array('required' => 'required','autofocus' => 'autofocus', 'placeholder' => 'direccion de domicilio','size'=>'40')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('main_phone','Telefono ') !!}</td>
 					<td>{!! Form::input('text','main_phone', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Telefono','size'=>'15')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('secondary_addr','Direccion Alternativa') !!}</td>
 					<td>{!! Form::input('text','secondary_addr',null ,array('autofocus' => 'autofocus', 'placeholder' => 'Direccion 2','size'=>'40')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('main_phone','Telefono 2 ') !!}</td>
 					<td>{!! Form::input('text','secondary_phone', null ,array('autofocus' => 'autofocus', 'placeholder' => 'Telefono2','size'=>'15')) !!}</td>
 				</tr>
 				<tr>
 					<td>{!! Form::label('referer','Referidor') !!}</td>
 					<td>{!! Form::input('text','referer', null 	,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Referido por','size'=>'15')) !!}</td>
 				</tr>
 				
 				<tr>
 					<td>{!! Form::label('active','Estado',['hidden']) !!}</td>
 					<td>{!! Form::checkbox('active',1,true,['hidden']) !!}</td>
 				</tr>
 			</table>
 		</td>
 	</table>
 @endsection

 @section('contenido2')
 	<div class="container" >
	 	<div class="form-group">
	 		{!! Form::submit('Crear',['class'=> 'btn btn-primary']) !!}
	 	</div>
	 </div>
 	{!! Form::close() !!}
@endsection