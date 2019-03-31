{{--  dd($objects['payment'])--}}
@extends('admin/template/main')
@section('title', 'Pagos')
@section('entidad') 
 Imprimir Resumen de pago
@endsection


@section('boton')
	<p align="right">
 		{{--<a href="{{ route ('home') }}" class="print-link no-print btn btn-primary">Continuar</a>--}}
 		{{--<a href="{{ route ('payment.list',['credit' => Crypt::encrypt($objects['credit']->id) ]) }}" class="print-link no-print btn btn-primary">Ver Pagos</a>--}}
 		<a href="{{ route ('payment.list',['credit' => $objects['credit']->id ]) }}" class="print-link no-print btn btn-primary">Ver Pagos</a>
 		<button class="print-link no-print btn btn-info" onclick="jQuery('#ele3').print({
 			globalStyles: true,
 			title:null,
 			timeout: 20000})"><img src="{{ URL::asset('img/imp.png') }}" class="admin-logo-nav" alt="profile Pic" height="20" width="20" > Imprimir</button>
 	</p>
@endsection
<div >
@section('contenido')
<table class="table table-layout bg-light" id="ele3" size="A5" >
	<thead ><td align="text-center"><a allign="center"> <img src="{{ URL::asset('img/logo.png') }}" class="admin-logo-nav" alt="profile Pic" height="25" width="25" > </a>Distribucion de Pago Realizado</td><td>Cliente {{$objects['payment']->document}}</td>
	</thead>

	<td>
	<table  class="table table-striped"  border="1">
		<tr>
			<thead>
				<th>Pago</th>
				<th class="text-center">{{$objects['payment']->id}}</th>
			</thead>
			<tbody>
				<tr>
				<td style="width:30%"> Fecha Pago </td>
				<td style="width:20%" align="right">{{ $objects['payment']->created_at }}</td>
				</tr>
				<tr>
				<td style="width:15%"> Valor </td>
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
				<tr>
				<td style="width:10%"> Elaboró </td>
				<td style="width:20%" align="right">{!! $objects['payment']->user_create !!}</td>
				</tr>
			</tbody>
		</tr>
	</table>
	</td>	
	<td class="table-striped">
	<table  class="table table-striped" ; border="1">
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
				<td style="width:20%"> Valor Desembolso </td>
				<td style="width:20%" align="right">${{ number_format($objects['credit']->valor_desembolso) }}</td>
				</tr>
				<tr>
				<td style="width:10%"> Tasa % </td>
				<td style="width:20%" align="right">{{ $objects['credit']->tasa_mensual }}%</td>
				</tr>
			</tbody>
		</tr>
	</table>
	<h4 class="table-danger"> {!! Form::label('credito','Dias de mora: '.$objects['dias']) !!} </h4>
	<h4 class="content-danger"> {!! Form::label('credito','Devolucion: '.$objects['devolucion']) !!} </h4>
	</td>
</table>

@endsection
</div>
@section('contenido2')
	<h4> {!! Form::label('credito','valor en mora:'.$objects['intmora']) !!} </h4>
	
@endsection
