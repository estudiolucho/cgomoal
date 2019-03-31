@extends('admin/template/main')
@section('title', 'Usuarios')
@section('entidad') 
 Lista de Usuarios 
@endsection

@section('boton')
@endsection
@section('contenido') 
<table   border="1">
	<tr>
	<thead>
		<th style="width:1%">Id</th>
		<th style="width:1%">Documento</th>
		<th style="width:10%">Nombres</th>
		<th style="width:10%">Apellidos</th>
		<th>Usuario</th>
		{{--<th>Clave</th>--}}
		<th>Acceso</th>
		<th>Tipo</th>
		<th>email</th>
		<th>Direccion</th>
		<th>Telefono</th>
		<th>Direccion2</th>
		<th>Telefono2</th>
		<th>Estado</th>
		<th>Creado por</th>
	</thead>
	<tbody>
		@foreach($users as $user)
		<tr>
			<td>{{ $user->id }}</td>
			<td>{{ $user->document }}</td>
			<td>{{ $user->name }}  </td>
			<td>{{ $user->lastname }}</td>
			<td>{{ $user->username }}</td>
			{{--<td>{{ $user->password }}</td>--}}
			<td>{{ $user->role_id }}</td>
			<td>{{ $user->type }}</td>
			<td>{{ $user->email }}</td>
			<td>{{ $user->main_addr }}</td>
			<td>{{ $user->main_phone }}</td>
			<td>{{ $user->secondary_addr }}</td>
			<td>{{ $user->secondary_phone }}</td>
			@if( $user->active  == 1)
			<td> Activo </td>
			@else
			<td> Inactivo </td>
			@endif
			<td>{{ $user->user_create }}</td>
		</tr>
		@endforeach
	</tbody>
	</tr>
 </table>
@endsection
@section('contenido2')
<p align="center" >
<a href="{{ route ('user.index') }}" type="button" class="btn btn-default">nada aqui</a>
 	</p>

@endsection