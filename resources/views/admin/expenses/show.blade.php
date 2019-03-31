{{-- dd($concept)--}}
@extends('admin/template/main')
@section('title', 'Aportes')
@section('entidad') 
 <a class="no-print">Imprimir Gasto</a>
@endsection


@section('boton')
	<p align="right">
 		<a href="{{ url()->previous() }}" class="no-print btn btn-primary">Volver</a>
 		<button class="print-link no-print btn btn-info" onclick="jQuery('#ele1').print({
 			globalStyles: true,
 			title:null,
 			timeout: 20000})"><img src="{{ URL::asset('img/imp.png') }}" class="admin-logo-nav" alt="profile Pic" height="20" width="20" >Imprimir</button>
 	</p>
@endsection

@section('contenido')
<table id="ele3" size="A5" style="width:60% align:left"  border="1">
	@section('title2')
	Fondo Gomoa
	@endsection
	<div class="container bg-light" style="width:50%">
	    <table  class="table table-hover table-striped table-sm
	          table-condensed tasks-table table-responsive" id="ele5" border="1" style="width:40%" >
			<tr>
				<thead>
					<th >Aporte</th>
					<th class="text-center">{{$expense->id}}</th>
				</thead>
				<tbody>
					<tr>
					<td style="width:15%"> Concepto </td>
					<td style="width:20%" align="right"><strong>{!! $concept->concept !!}</strong></td>
					</tr>
					<tr>
					<td style="width:10%"> Fecha Aporte </td>
					<td style="width:20%" align="right">{!! $expense->created_at !!}</td>
					</tr>
					<tr>
					<td style="width:15%"> Valor </td>
					<td style="width:20%" align="right"><strong>${{ number_format($expense->amount) }}</strong></td>
					</tr>
					<tr>
					<td style="width:10%"> Elaboró </td>
					<td style="width:20%" align="right">{!! $expense->user_create !!}</td>
					</tr>
				</tbody>
			</tr>
		</table>
	</div>
</table>
@endsection