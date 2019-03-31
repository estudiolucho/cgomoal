@extends('admin/template/main')
@section('title', 'Pagos')
@section('entidad') 
 Gastos Realizados
 <h2>{{ $id }} </h2>
@endsection

@section('herramientas')

	<div class="panel no-print" align="center">
		<a >Descargar en Plano</a>
	</div>
	<div class="panel" align="center">
	<a href="{{ URL::to('reports/expenses/downloadExcel/xls') }}"><img src="{{ URL::asset('img/expXLS.gif') }}" class="admin-logo-nav" alt="profile Pic" height="25" width="25" ></a>
	{{--
	<a href="{{ URL::to('reports/expenses/downloadExcel/xls') }}"><button class="btn btn-secondary no-print text-primary" style="height:37px;width:60px" >xls</button></a>
	<a href="{{ URL::to('reports/expenses/downloadExcel/xlsx') }}"><button class="btn btn-secondary no-print text-primary" style="height:37px;width:60px">xlsx</button></a>
	<a href="{{ URL::to('reports/downloadExcel/csv') }}"><button class="btn btn-success no-print">CSV</button></a>--}}
	</div>
@endsection

@section('boton')
	<p align="right">
 	<a href="{{ url()->previous() }}" class="no-print btn btn-primary">Volver</a>

 	<button class="print-link no-print btn btn-info" onclick="jQuery('#ele4').print({
 			globalStyles: true,
 			title:null,
 			timeout: 20000})"> Imprimir</button>
 	</p>
 	<p align="right"></p>
@endsection

@section('contenido')
<body>
	<div class="container" align="center">
		{{--
		<a href="{{ URL::to('reports/downloadExcel/xls') }}"><button class="btn btn-success no-print">Download Excel xls</button></a>
		<a href="{{ URL::to('reports/downloadExcel/xlsx') }}"><button class="btn btn-success no-print">Download Excel xlsx</button></a>
		<a href="{{ URL::to('reports/downloadExcel/csv') }}"><button class="btn btn-success no-print">Download CSV</button></a>
		--}}
		
		{{-- usar PARA SUBIR ARCHIVOS
		<form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
			<input type="file" name="import_file" />
			<button class="btn btn-primary">Import File</button>
		</form>
		--}}
	</div>
</body>


	
<table  class="table table-striped table-sm" border="1">
	<tr>
	<thead>
		<th>#</th>
		<th>Concepto</th>
		<th>Fecha </th>
		<th>Fecha realizacion</th>
		<th>Valor </th>
		<th>Saldo Gastos</th>
		<th>Descripcion</th>
	</thead>
	<tbody>
		<?php 
			$totgastos=0;
			?>
		@foreach($expenses as $expense)
		<tr>
			<td>{{ $expense->id }}</td>
			<td>{{ $expense_concepts->find($expense->concept_id)->concept }}</td>
			<td>{{ $expense->expense_date }}</td>
			<td>{{ $expense->created_at }}</td>
			<td align="right"> <strong> ${{ number_format($expense->amount) }} </strong> </td>
			<?php 	$totgastos=$totgastos+$expense->amount;?>
			<td align="right">${{ number_format($totgastos) }}</td>
			<td>{{ $expense->description }}</td>
			{{--
			<td>
				<a href="#" class="no-print btn btn-info">Ver</a>
			  	@if(Auth::user()->role_id === 1 )
			  	<a href="{{ route('expense.edit',$expense->id) }}" class="no-print btn btn-danger "> Editar </a>
			  	@endif
			</td>
			--}}		

			
		</tr>
		@endforeach
	</tbody>
	</tr>
 </table>
 {!! Form::label('totales','Total Gastos en  lapso : '.$totgastos) !!}
@endsection

@section('contenido2')

@endsection

