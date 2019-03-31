@extends('admin/template/main')
@section('title', 'Pagos')
@section('entidad') 
 Aportes Realizados agrupados
@endsection
@section('herramientas')
	<div class="panel no-print" align="center">
		<a >Descargar en Plano</a>
	</div>
	<div class="panel" align="center">
	<a href="{{ URL::to('reports/downloadExcel2/xls') }}"><img src="{{ URL::asset('img/expXLS.gif') }}" class="admin-logo-nav" alt="profile Pic" height="25" width="25" ></a>
	{{--<a href="{{ URL::to('reports/downloadExcel2/xls') }}"><button class="btn btn-secondary no-print text-primary" style="height:37px;width:60px" >xls</button></a>
	<a href="{{ URL::to('reports/downloadExcel2/xlsx') }}"><button class="btn btn-secondary no-print text-primary" style="height:37px;width:60px">xlsx</button></a>
	<a href="{{ URL::to('reports/downloadExcel2/csv') }}"><button class="btn btn-success no-print">CSV</button></a>--}}
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
	@section('title2')
	{{ $id }}
	@endsection
<table  class="table table-striped table-sm" border="1">
	<tr>
	<thead>
		<th>Socio</th>
		<th>Concepto</th>
		<th>Saldo Aportes</th>
		<th>Acumulado General</th>
	</thead>
	<tbody>
		<?php 
			$totabonos=0;
			?>
		@foreach($contributions as $contribution)
		<tr>
			<td>{{ $user->find($contribution->user_id)->document }}| 
			    {{ $user->find($contribution->user_id)->name }} </td>
			<td>{{ $contribution_concepts->find($contribution->concept_id)->concept }}</td>
			
			<td align="right"> <strong> ${{ number_format($contribution->total) }} </strong> </td>
			<?php 	$totabonos=$totabonos+$contribution->total;?>
			<td align="right">${{ number_format($totabonos) }}</td>
			
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
 {!! Form::label('totales','Total Aportes en  lapso : '.$totabonos) !!}
 
@endsection