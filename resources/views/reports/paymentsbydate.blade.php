@extends('admin/template/main')
@section('title', 'Pagos')
@section('entidad') 
 Pagos Realizados
 <h2>{{ $id }} </h2>


@endsection
@section('herramientas')
	<div class="panel no-print" align="center">
		<a >Descargar en Plano</a>
	</div>
	<div class="panel" align="center">
	<a href="{{ URL::to('reports/payments/downloadExcel/xls') }}"><img src="{{ URL::asset('img/expXLS.gif') }}" class="admin-logo-nav" alt="profile Pic" height="25" width="25" ></a>
	{{--<a href="{{ URL::to('reports/payments/downloadExcel/xls') }}"><button class="btn btn-secondary no-print text-primary" style="height:37px;width:60px" >xls</button></a>
	<a href="{{ URL::to('reports/payments/downloadExcel/xlsx') }}"><button class="btn btn-secondary no-print text-primary" style="height:37px;width:60px">xlsx</button></a>
	<a href="{{ URL::to('reports/payments/downloadExcel/csv') }}"><button class="btn btn-success no-print">CSV</button></a>--}}
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
<table  class="table table-striped table-sm" border="1">
	<tr>
	<thead>
		<th>#</th>
		<th>Cliente</th>
		<th>Cred</th>
		<th align="right">Fecha aplicacion Pago</th>
		<th>Fecha realizacion Pago</th>
		<th>Valor Pagado</th>
		<th>Interes Mora</th>
		<th>Saldo Mora</th>
		<th>Abono Int</th>
		<th>Abono Capital</th>
		<th>Saldo Int</th>
		<th>Saldo Capital</th>
		<th>Descripcion</th>
	</thead>
	<tbody>
		<?php 
			$totabonos=0;
			$totintmora=0;
			$totaboint=0;
			$totabocap=0;  ?>
		@foreach($payments as $payment)
		<tr>
			<td>{{ $payment->id }}</td>
			<td>{{$payment->document}}|{{ $users->where('document',$payment->document)->first()->name}} {{$users->where('document',$payment->document)->first()->lastname}} </td>
			<td>{{ $payment->credit_id }}</td>
			<td>{{ $payment->date_payment }}</td>
			<td>{{ $payment->created_at }}</td>
			<td align="right"> <strong> ${{ number_format($payment->amount) }} </strong> </td>
			<td align="right">${{ number_format($payment->intmora) }}</td>
			<td align="right">${{ number_format($payment->saldointmora) }}</td>
			<td align="right"> <strong> ${{ number_format($payment->abono_interes) }} </strong> </td>
			<td align="right"><strong> ${{ number_format($payment->abono_capital) }} </strong> </td>
			<td align="right">${{ number_format($payment->saldo_interes) }}</td>
			<td align="right">${{ number_format($payment->saldo_capital) }}</td>
			<td>{{ $payment->descripcion }}</td>
			{{--
			<td>
				<a href="#" class="no-print btn btn-info">Ver</a>
			  	@if(Auth::user()->role_id === 1 )
			  	<a href="{{ route('payment.edit',$payment->id) }}" class="no-print btn btn-danger "> Editar </a>
			  	@endif
			</td>
			--}}		
		</tr>
		<?php 	$totabonos=$totabonos+$payment->amount; 
				$totintmora=$totintmora+$payment->intmora;
				$totaboint=$totaboint+$payment->abono_interes;
				$totabocap=$totabocap+$payment->abono_capital ?>
		@endforeach
	</tbody>
	</tr>
 </table>
 {!! Form::label('totales','Total Abonos: '.$totabonos.' '.'Total Mora Pagada: '.$totintmora.' '.'Total Interes Pagado: '.$totaboint.' '.' Total Capital Pagado: '.$totabocap) !!}
 {{-- {!! $payments->render() !!} --}}
@endsection