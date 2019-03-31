@extends('admin.template.main')
@section('title','Gastos')
<body>
	@section('entidad')
		Gasto / {{ $expense->id}}
	@endsection
	<table  border="1">
	<tr>
		<th>Codigo</th>
		<th>ID de Concepto</th>
		<th>Fecha de Gasto</th>
		<th>Monto</th>
		<th>Descripcion</th>
		<th>Fecha Creacion</th>
		<th>Fecha Modificacion</th>
		<th>Acciones</th>
		<tr>
		  	<td>{{$expense->id }}</td>
		  	<td>{{$expense->concept_id }}</td>
		  	<td>{{$expense->expense_date }}</td>
		  	<td>{{$expense->amount }}</td>
		  	<td>{{$expense->description }}</td>
		  	<td>{{$expense->created_at }}</td>
		  	<td>{{$expense->updated_at }}</td>
		  	<td>
		  		<a href="#" class="btn btn-sm btn-info">Ver</a>
		  		<a href="#" class="btn btn-sm btn-primary">Editarrrrrrrrrrrrrrrrrr</a>
		  	</td>

		 </tr>
	</tr>
	<hr>
</body>