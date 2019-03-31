@extends('admin/template/main')
@section('title', 'Gastos')
@section('entidad') 
Conceptos de Gastos
@endsection
@section('boton')
	<p align="left">
 		<a href="{{ route ('econcept.create') }}" class="btn btn-primary">Nuevo Concepto de  Gasto</a>
 	</p>
@endsection
@section('contenido') 
<table  class="table table-striped table-sm" border="1">
	<tr>
	<thead>
		<th>ID</th>
		<th>Concepto</th>
		<th>Descripcion</th>
		<th>Estado</th>
	</thead>
	<tbody>
		@foreach($econcepts as $econcept)
		<tr>
		<td>{{ $econcept->id }}</td>
		<td>{{ $econcept->concept }}</td>
		<td>{{ $econcept->description }}</td>
		@if($econcept->active === 1) 
		<td>{!! Form::checkbox('active',$econcept->active, true) !!}  </td>
		@else
		<td>{!! Form::checkbox('active',$econcept->active, false) !!}  </td>
		@endif

		{{--
		<td>
			<a href="#" class="btn btn-info">Ver-pte</a>
			@if(Auth::user()->role_id === 1 )
		  	<a href="{{ route('econcept.edit',$econcept->id) }}" class="btn btn-danger">Editar 
		  	</a>
		  	@endif
		</td>
		--}}
		
		</tr>
		@endforeach
	</tbody>
	</tr>
 </table>
 {!! $econcepts->render() !!}
@endsection