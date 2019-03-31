{{--  dd($objects['payment'])--}}
@extends('admin/template/main')
@section('title', 'Pagos')
@section('entidad') 
 Resumen de pago no cambia el boton 
@endsection
@section('boton')
	<p align="right">
 		<a href="{{ route ('home') }}" class="print-link no-print btn btn-primary">Continuar</a>
 		<button class="print-link no-print btn btn-info" onclick="jQuery('#ele4').print({
 			globalStyles: true,
 			title:null,
 			timeout: 20000})"> Imprimir</button>
 	</p>
@endsection
@section('contenido')

	<table  class="table table-striped"; style="width:40%" ; border="1">
	<tr>
		<thead>
			<th>Credito</th>
			<th class="text-center">{{$objects['credit']->id}}</th>
		</thead>
		<tbody>
			<tr>
			<td style="width:10%"> Fecha Credito</td>
			<td style="width:30%" align="right">{{ $objects['credit']->fecha_desembolso }}</td>
			</tr>
			<tr>
			<td style="width:10%"> Valor Desembolso </td>
			<td style="width:20%" align="right">${{ number_format($objects['credit']->valor_desembolso) }}</td>
			</tr>
			<tr>
			<td style="width:10%"> Tasa % </td>
			<td style="width:20%" align="right">{{ $objects['credit']->tasa_mensual }}%</td>
			</tr>
			<td style="width:10%"> Saldo Interes</td>
			<td style="width:20%" align="right">${{ number_format($objects['credit']->saldo_interes) }}</td>
			</tr>
			<tr>
			<td style="width:10%"> Saldo Capital</td>
			<td style="width:20%" align="right">${{ number_format($objects['credit']->saldo_capital) }}</td>
			</tr>
		</tbody>
	</tr>
	</table>
	<hr>
	<table  class="table table-striped"; style="width:40%" ;border="1">
	<tr>
		<thead>
			<th>Pago</th>
			<th class="text-center">{{$objects['payment']->id}}</th>
		</thead>
		<tbody>
			<tr>
			<td style="width:10%"> Fecha Pago </td>
			<td style="width:20%" align="right">{{ $objects['payment']->date_payment }}</td>
			</tr>
			<tr>
			<td style="width:10%"> Valor </td>
			<td style="width:20%" align="right"><strong>${{ number_format($objects['payment']->amount) }}</strong></td>
			</tr>
			<tr>
			<td style="width:10%"> Int Mora descontado </td>
			<td style="width:20%" align="right">${{ number_format($objects['payment']->intmora) }}</td>
			</tr>
			<tr>
			<td style="width:10%"> Saldo Int Mora </td>
			<td style="width:20%" align="right">${{ number_format($objects['payment']->saldointmora) }}</td>
			</tr>
			<tr>
			<td style="width:10%"> Abono Capital </td>
			<td style="width:20%" align="right">${{ number_format($objects['payment']->abono_capital) }}</td>
			</tr>
			<tr>
			<td style="width:10%"> Abono Interes </td>
			<td style="width:20%" align="right">${{ number_format($objects['payment']->abono_interes) }}</td>
			</tr>
			<tr>
			<td style="width:10%"> Saldo Interes </td>
			<td style="width:20%" align="right">${{ number_format($objects['payment']->saldo_interes) }}</td>
			</tr>
			<tr>
			<td style="width:10%"> Saldo Capital </td>
			<td style="width:20%" align="right"><strong>${{ number_format($objects['payment']->saldo_capital) }}</strong></td>
			</tr>
		</tbody>
	</tr>
@endsection
@section('contenido2')
	<h4> {!! Form::label('credito','Dias de mora:'.$objects['dias']) !!} </h4>
	<h4> {!! Form::label('credito','valor en mora:'.$objects['intmora']) !!} </h4>
	<h4> {!! Form::label('credito','Devolucion: '.$objects['devolucion']) !!} </h4>
@endsection
