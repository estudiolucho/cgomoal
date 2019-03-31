@extends('admin/template/main')
@section('title', 'Flujo de Caja')
@section('entidad') 
 Registro Diario
@endsection
@section('boton')
	<button class="print-link no-print btn btn-info" onclick="jQuery('#ele4').print({
 			globalStyles: true,
 			title:null,
 			timeout: 20000})"> Imprimir</button>
 	</p>
@endsection
@section('herramientas')
	<div class="panel no-print" align="center">
		<a >Descargar en Plano</a>
	</div>
	<div class="panel" align="center">
	<a href="{{ URL::to('reports/downloadExcelFlujo/xls') }}">
		<img src="{{ URL::asset('img/expXLS.gif') }}" class="admin-logo-nav" alt="profile Pic" height="25" width="25" ></a>
	{{--<a href="{{ URL::to('reports/downloadExcelFlujo/xls') }}"><button class="btn btn-secondary no-print text-primary " >xls</button></a>
	<a href="{{ URL::to('reports/downloadExcelFlujo/xlsx') }}"><button class="btn btn-secondary no-print text-primary">xlsx</button></a>
	<a href="{{ URL::to('reports/downloadExcelFlujo/csv') }}"><button class="btn btn-success no-print">CSV</button></a>--}}
	</div>
@endsection
@section('contenido') 
<table  class="table table-striped" border="1">
	<tr>
	<thead>
		<th>Num</th>
		<th style="width: 110px;">Fecha</th>
		<th>Concepto</th>
		<th>Tipo</th>
		<th style="width: 130px;">Valor</th>
		<th style="width: 140px;">Saldo</th>
		<th>Descripcion</th>
		<th>Elaboro</th>
	</thead>
	<tbody>
		@foreach($cash_flow as $cash)
		<tr>
		<td>{{ $cash->id }}</td>
		<td>{{ $cash->date }}</td>
		<td>{{ $cash->concept }}</td>
		<td >{{ $cash->type }}</td>
		<td align="right">$ {{ number_format($cash->amount,2) }}</td>
		<td align="right">$ {{ number_format($cash->balance,1) }}</td>
		<td>{{ $cash->description }}</td>
		<td>{{ $cash->user_create }}</td>
		
		</tr>
		@endforeach
	</tbody>
	</tr>
 </table>
 {{--{!! $contributions->render() !!}--}}
@endsection