@extends('admin/template/main')
@section('title', 'Aportes')
@section('entidad') 
Conceptos de Aportes
@endsection
@section('boton')
	<p align="left">
 		<a href="{{ route ('cconcept.create') }}" class="btn btn-primary">Nuevo Concepto de  Aporte</a>
 	</p>
@endsection
@section('contenido') 
<table  class="table table-striped" border="1">
	<tr>
	<thead>
		<th>ID</th>
		<th>Concepto</th>
		<th>Descripcion</th>
		<th>Estado</th>
	</thead>
	<tbody>
		@foreach($cconcepts as $cconcept)
		<tr>
		<td>{{ $cconcept->id }}</td>
		<td>{{ $cconcept->concept }}</td>
		<td>{{ $cconcept->description }}</td>
		@if($cconcept->active === 1) 
		<td>{!! Form::checkbox('active',$cconcept->active, true) !!}  </td>
		@else
		<td>{!! Form::checkbox('active',$cconcept->active, false) !!}  </td>
		@endif

		{{--
		<td>
			<a href="#" class="btn btn-info">Ver-pte</a>
		  	<a href="{{ route('cconcept.edit',$cconcept->id) }}" class="btn btn-danger">Editar </a>
		</td>
		--}}
		
		</tr>
		@endforeach
	</tbody>
	</tr>
 </table>
 {!! $cconcepts->render() !!}
@endsection