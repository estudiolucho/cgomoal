@extends('admin/template/main')
@section('title', 'Pagos')
@section('entidad') 
 Editar Resumen de pago
@endsection


@section('boton')
	<p align="right">
 		{{--<a href="{{ route ('home') }}" class="print-link no-print btn btn-primary">Continuar</a>
 		--}}
 		<button class="print-link no-print btn btn-info" onclick="jQuery('#ele3').print({
 			globalStyles: true,
 			title:null,
 			timeout: 20000})"><img src="{{ URL::asset('img/imp.png') }}" class="admin-logo-nav" alt="profile Pic" height="20" width="20" > Imprimir</button>
 	</p>
@endsection

@section('contenido')
<table class="table table-layout bg-light" id="ele3" size="A5" >
	<thead ><td align="text-center">Distribucion de Pago Realizado</td><td>Cliente {{$payment->document}}</td>
	</thead>
	<td class="table-info">
	<table  class="table table-striped" ; border="1">
		<tr>
			<thead>
				<th>Pago</th>
				<th class="text-center">{{$payment->id}}</th>
			</thead>
			<tbody>
				<tr>
				<td style="width:10%"> Fecha Pago </td>
				<td style="width:20%" align="right">{{ $payment->date_payment }}</td>
				</tr>
				<tr>
				<td style="width:15%"> Valor </td>
				<td style="width:20%" align="right"><strong>${{ number_format($payment->amount) }}</strong></td>
				</tr>
				<tr>
				<td style="width:10%"> Int Mora descontado </td>
				<td style="width:20%" align="right">${{ number_format($payment->intmora) }}</td>
				</tr>
				<tr>
				<td style="width:10%"> Saldo Int Mora </td>
				<td style="width:20%" align="right">${{ number_format($payment->saldointmora) }}</td>
				</tr>
				<tr>
				<td style="width:10%"> Abono Capital </td>
				<td style="width:20%" align="right">${{ number_format($payment->abono_capital) }}</td>
				</tr>
				<tr>
				<td style="width:10%"> Abono Interes </td>
				<td style="width:20%" align="right">${{ number_format($payment->abono_interes) }}</td>
				</tr>
				<tr>
				<td style="width:10%"> Saldo Interes </td>
				<td style="width:20%" align="right">${{ number_format($payment->saldo_interes) }}</td>
				</tr>
				<tr>
				<td style="width:10%"> Saldo Capital </td>
				<td style="width:20%" align="right"><strong>${{ number_format($payment->saldo_capital) }}</strong></td>
				</tr>
			</tbody>
		</tr>
	</table>
	</td>
</table>
@endsection

@section('contenido2')
	
@endsection
