@extends('admin/template/main')
@section('title', 'Pagos')
@section('entidad') 
 Ver Resumen de pago
 <h2>Credito {{ $payment->credit_id }} </h2>
@endsection


@section('boton')
	<p align="right">
 		{{--<a href="{{ route('payment.list',['credit' => Crypt::encrypt($credit->id) ]) }}" class="btn btn-primary">Volver</a>--}}
 		<a href="{{ route('payment.list',['credit' => $credit->id ]) }}" class="btn btn-primary">Volver</a>
 		<button class="print-link no-print btn btn-info" onclick="jQuery('#ele1').print({
 			globalStyles: true,
 			title:null,
 			timeout: 20000})"><img src="{{ URL::asset('img/imp.png') }}" class="admin-logo-nav" alt="profile Pic" height="20" width="20" > Imprimir</button>
 	</p>
@endsection

@section('contenido')
@section('title2')
	<h3 align="left"><a allign="center"> <img src="{{ URL::asset('img/logo.png') }}" class="admin-logo-nav" alt="profile Pic" height="25" width="25" > </a>Fondo Gomoa </h3>
	<h5 align="left">Pago Realizado	Cliente {{$payment->document}}</h5>
@endsection
<table class="table  table-responsive table-striped" id="ele3" size="A5"  >
			<thead>
				<th class="text-left">Recibo de Pago #</th>
				<th class="text-right">{{$payment->id}}</th>
			</thead>
			<tbody>
				<tr>
				<td width="50%"> Credito #</td>
				<td width="50%" align="right">{{ $payment->credit_id }}</td>
				</tr>
				<tr>
				<td > Fecha Pago </td>
				<td align="right">{{ $payment->created_at }}</td>
				</tr>
				<tr>
				<td > Valor </td>
				<td  align="right"><strong>${{ number_format($payment->amount) }}</strong></td>
				</tr>
				<tr>
				<td > Int Mora </td>
				<td  align="right">${{ number_format($payment->intmora) }}</td>
				</tr>
				<tr>
				<td > Saldo Int Mora </td>
				<td  align="right">${{ number_format($payment->saldointmora) }}</td>
				</tr>
				<tr>
				<td > Abono Capital </td>
				<td  align="right">${{ number_format($payment->abono_capital) }}</td>
				</tr>
				<tr>
				<td > Abono Interes </td>
				<td  align="right">${{ number_format($payment->abono_interes) }}</td>
				</tr>
				<tr>
				<td > Saldo Interes </td>
				<td  align="right">${{ number_format($payment->saldo_interes) }}</td>
				</tr>
				<tr>
				<td > Saldo Capital </td>
				<td  align="right"><strong>${{ number_format($payment->saldo_capital) }}</strong></td>
				</tr>
				<tr>
				<td style="width:10%"> Elaboró </td>
				<td style="width:20%" align="right">{!! $payment->user_create !!}</td>
				</tr>
			</tbody>
</table>
@endsection

@section('contenido2')
	
@endsection
