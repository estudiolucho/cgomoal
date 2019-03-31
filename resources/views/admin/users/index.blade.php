@extends('admin/template/main')
@section('title', 'Usuarios')
@section('entidad') 
 Usuarios Registrados
@endsection
@section('herramientas')
{{--
{!! Form::open(['route' => 'user.find', 'method'=>'POST']) !!}
	<div class="form-group">
		{!! Form::label('identificacion','Cedula') !!}
		{!! Form::input('text','document','',['size'=>'15','placeholder'=>'ingrese documento']) !!}
		{!! Form::submit('Buscar',['class'=> 'btn btn-primary']) !!}
	</div>
{!! Form::close() !!}
--}}
<form action="/search" method="POST" role="search">
        {{ csrf_field() }}
        <div class="input-group">
            <input type="text" name="q" maxlength="33" size="33"
                placeholder="Buscar por nombre, cedula, correo"> <span class="input-group-btn">
                <button type="submit" class="btn btn-sm btn-default">Ir
                    <span class="glyphicon glyphicon-search"></span>
                </button>
            </span>
        </div>
</form>
@endsection
@section('boton')
<p align="center">
 	<a href="{{ route ('user.create') }}" class="btn btn-secondary text-primary">Nuevo Usuario <img src="{{ URL::asset('img/users.png') }}" class="admin-logo-nav" title="Nuevo Usuario" alt="profile Pic" height="25" width="25" ></a>
 	</p>
 @endsection
@section('contenido') 
<table  class="table table-striped table-sm " border="1">
	<tr>
	<thead>
		<th style="width:1%">Documento</th>
		<th style="width:10%">Nombre</th>
		<th>Usuario</th>
		<th>email</th>
		<th>Direccion</th>
		<th>Telefono</th>
		<th>Tipo / Estado</th>
		<th>Acciones</th>
	</thead>
	<tbody>
		@foreach($users as $user)
		<tr>
			<td>{{ $user->document }}</td>
			<td>{{ $user->name }}  {{ $user->lastname }}</td>
			<td>{{ $user->username }}</td>
			<td>{{ $user->email }}</td>
			<td>{{ $user->main_addr }}</td>
			<td>{{ $user->main_phone }}</td>
			
			<td>{{ $user->type }} /
				@if( $user->active  == 1)
			 Activo 
			@else
			Inactivo 
			@endif

			</td>
			<td>
				@if(Auth::user()->role_id === 1 or Auth::user()->role_id === 2)
				<a href="{{route('user.edit',$user->id)}}" class="btn btn-sm btn-secondary text-danger"><img src="{{ URL::asset('img/edit.png') }}" class="admin-logo-nav" title="Editar Usuario" alt="profile Pic" height="25" width="25" ></a>
				@endif
				@if($user->active === 1)
				<a href="{{route('credit.createbyuser',$user->id)}}" class="btn btn-sm btn-secondary text-info" ><img src="{{ URL::asset('img/cred.png') }}" class="admin-logo-nav" title="Nuevo Credito" alt="profile Pic" height="25" width="25" ></a>
				@endif
				{{--<a href="{{route('payment.posfindcredit',Crypt::encrypt($user->document))}}" class="btn btn-secondary text-primary">Ver Cred</a>--}}
				<a href="{{route('payment.posfindcredit',$user->document)}}" class="btn btn-sm btn-secondary text-primary"><img src="{{ URL::asset('img/view.png') }}" class="admin-logo-nav" title="Ver Creditos" alt="profile Pic" height="25" width="25" ></a>
			</td>		
		</tr>
		@endforeach
	</tbody>
	</tr>
 </table>
 {!! $users->render() !!} 
@endsection
@section('contenido2')
<p align="center" >
<a href="{{ route ('user.index') }}" type="button" class="btn btn-default">nada aqui</a>
 	</p>

@endsection