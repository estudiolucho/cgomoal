@extends('admin/template/main')
@section('title', 'Pagos')
@section('entidad') 
 Aportes por Lapso por Socio 
 <h2>{{ $id }} </h2>
@endsection

@section('herramientas')
	@if(Auth::user()->isOperator() or Auth::user()->isAdmin())
	<div class="panel no-print" align="center">
		<a >Descargar en Plano</a>
	</div>
	<div class="panel" align="center">
	<a href="{{ URL::to('reports/downloadExcel4/xls') }}">
		<img src="{{ URL::asset('img/expXLS.gif') }}" class="admin-logo-nav" alt="profile Pic" height="25" width="25" ></a>
	{{--<a href="{{ URL::to('reports/downloadExcel3/xls') }}"><button class="btn btn-secondary no-print text-primary" style="height:37px;width:60px" >xls</button></a>
	<a href="{{ URL::to('reports/downloadExcel3/xlsx') }}"><button class="btn btn-secondary no-print text-primary" style="height:37px;width:60px">xlsx</button></a>
	<a href="{{ URL::to('reports/downloadExcel3/csv') }}"><button class="btn btn-success no-print">CSV</button></a>--}}
	</div>
	@endif
@endsection

@section('boton')
	<p align="right">
 	<a href="{{ url()->previous() }}" class="no-print btn btn-primary">Volver</a>

 	<button class="print-link no-print btn btn-info" onclick="jQuery('#ele4').print({
 			globalStyles: true,
 			title:null,
 			timeout: 20000})"> <img src="{{ URL::asset('img/imp.png') }}" class="admin-logo-nav" alt="profile Pic" height="20" width="20" >Imprimir</button>
 	</p>
 	<p align="right"></p>
@endsection

@section('contenido')
{{--<head>
	<title>Import - Export Laravel 5</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" >
</head>
--}}
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
</html>

	
<table  class="table table-striped table-sm" border="1">
	<tr>
	<thead>
		<th>#</th>
		<th>Socio</th>
		<th>Concepto</th>
		<th>Fecha aplicacion Pago</th>
		<th>Fecha realizacion Pago</th>
		<th>Valor Pagado</th>
		<th>Saldo Aportes</th>
		<th>Descripcion</th>
	</thead>
	<tbody>
		<?php 
			$totabonos=0;
			?>
		@foreach($contributions as $contribution)
		<tr>
			<td>{{ $contribution->id }}</td>
			<td>{{ $user->find($contribution->user_id)->document }} </td>
			<td>{{ $contribution_concepts->find($contribution->concept_id)->concept }}</td>
			<td>{{ $contribution->contribution_date }}</td>
			<td>{{ $contribution->created_at }}</td>
			<td align="right"> <strong> ${{ number_format($contribution->amount) }} </strong> </td>
			<?php 	$totabonos=$totabonos+$contribution->amount;?>
			<td align="right">${{ number_format($totabonos) }}</td>
			<td>{{ $contribution->description }}</td>
			{{--
			<td>
				<a href="#" class="no-print btn btn-info">Ver</a>
			  	@if(Auth::user()->role_id === 1 )
			  	<a href="{{ route('contribution.edit',$contribution->id) }}" class="no-print btn btn-danger "> Editar </a>
			  	@endif
			</td>
			--}}		

			
		</tr>
		@endforeach
	</tbody>
	</tr>
 </table>
 {!! Form::label('totales','Total Aportes socio : '.$totabonos) !!}
@endsection

@section('contenido2')

@endsection

