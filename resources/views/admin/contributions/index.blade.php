@extends('admin/template/main')
@section('title', 'Aportes')
@section('entidad') 
 Lista de Aportes
@endsection
@section('boton')
	<p align="left">
 		<a href="{{ route ('contribution.create') }}" class="btn btn-secondary text-primary">Nuevo Aporte <img src="{{ URL::asset('img/contrib.png') }}" class="admin-logo-nav" title="Nuevo Aporte" alt="profile Pic" height="25" width="25" ></a>
 	</p>
@endsection
@section('contenido') 
<table  class="table table-sm table-striped" border="1">
	<tr>
	<thead>
		<th>Num</th>
		<th>Socio</th>
		<th>Concepto</th>
		<th>Fecha</th>
		<th>Valor</th>
		<th>Descripcion</th>
		<th>Acciones</th>
	</thead>
	<tbody>
		@foreach($contributions as $contribution)
		<tr>
		<td>{{ $contribution->id }}</td>
		<td>{{ $users->find($contribution->user_id)->name}} {{ $users->find($contribution->user_id)->lastname }}</td>
		<td>{{ $concepts->find($contribution->concept_id)->concept }}</td>
		<td>{{ $contribution->contribution_date }}</td>
		<td align="right">${{ number_format($contribution->amount) }}</td>
		<td>{{ $contribution->description }}</td>
		<td>
			<a href="{{ route('contribution.show',$contribution->id)}}" class="btn btn-sm btn-secondary text-info"><img src="{{ URL::asset('img/imp.png') }}" title="Imprimir" class="admin-logo-nav" alt="profile Pic" height="20" width="20" ></a>
			@if(Auth::user()->role_id === 1 )
		  	<a href="{{ route('contribution.edit',$contribution->id) }}" class="btn btn-sm btn-secondary text-danger"><img src="{{ URL::asset('img/edit.png') }}" title="Editar" class="admin-logo-nav" alt="profile Pic" height="20" width="20" ></a>
		  	@endif
		</td>
		
		</tr>
		@endforeach
	</tbody>
	</tr>
 </table>
 {!! $contributions->render() !!}
@endsection