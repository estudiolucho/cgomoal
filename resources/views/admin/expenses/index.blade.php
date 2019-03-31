@extends('admin/template/main')
@section('title', 'Gastos')
@section('entidad') 
 Lista de Gastos
@endsection
@section('boton')
	<p align="left">
 		<a href="{{ route ('expense.create') }}" class="btn btn-secondary text-primary">Nuevo Gasto <img src="{{ URL::asset('img/expense.png') }}" title="Nuevo Gasto" class="admin-logo-nav" alt="profile Pic" height="30" width="30"></a>
 	</p>
@endsection
@section('contenido') 
<table  class="table table-striped table-sm" border="1">
	<tr>
	<thead>
		<th>Num</th>
		<th>Concepto</th>
		<th>Fecha</th>
		<th>Valor</th>
		<th>Descripcion</th>
		<th>Elaboró</th>
		<th>Acciones</th>
	</thead>
	<tbody>
		@foreach($expenses as $expense)
		<tr>
			<td>{{ $expense->id }}</td>
			<td>{{ $concepts->find($expense->concept_id)->concept }}</td>
			<td>{{ $expense->expense_date }}</td>
			<td>{{ $expense->amount }}</td>
			<td>{{ $expense->description }}</td>
			<td>{{ $expense->user_create }}</td>
			<td>
				<a href="{{ route('expense.show',$expense->id) }}" class="btn btn-sm btn-secondary text-info"><img src="{{ URL::asset('img/imp.png') }}" class="admin-logo-nav" title="Imprimir" alt="profile Pic" height="20" width="20" ></a>
				@if(Auth::user()->role_id === 1 )
			  	<a href="{{ route('expense.edit',$expense->id) }}" class="btn btn-sm btn-secondary text-danger"><img src="{{ URL::asset('img/edit.png') }}" title="Editar" class="admin-logo-nav" alt="profile Pic" height="20" width="20" ></a>
			  	@endif
			</td>
		</tr>
		@endforeach
	</tbody>
	</tr>
 </table>
 {!! $expenses->render() !!}
@endsection