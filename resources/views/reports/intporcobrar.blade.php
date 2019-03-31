{{-- dd($credits,$intereses,$porcobrar) --}}
@extends('admin/template/main')
@section('title', 'Interes')
@section('entidad') 
 Intereses por aplicar

@endsection

@section('boton')
	<div id='masterContent'>
	<p align="right">
 		{{--
 		<a href="{{ route ('credit.create') }}" class="btn btn-info">Nuevo Credito</a>
 		--}}
 		<button class="print-link no-print btn btn-info" onclick="jQuery('#ele4').print({
 			globalStyles: true,
 			title:null,
 			timeout: 20000})"><img src="{{ URL::asset('img/imp.png') }}" class="admin-logo-nav" alt="profile Pic" height="20" width="20" >Imprimir</button>
 	</p>
 	</div>
 @endsection

 @section('title2')
 <caption align="center" class="caption"> Reporte Intereses por Aplicar</caption>
 @endsection

@section('contenido')
<table class="table table-sm table-striped" border="1">
	<tr>
	<thead>
		<th>Cr Num</th>
		<th align="center">Cliente</th>
		<th>Nombres y Apellidos</th>
		<th>Fec. Desemb (ult. abono) </th>
		<th>Desembolso</th>
		<th align="center">Tasa/(Cuotas)</th>
		<th>Saldo Rec. Mora</th>
		<th>Saldo Interes</th>
		<th>Saldo Capital</th>
	</thead>
	<tbody>
		<?php 
			$salcap=0;
			$salint=0;  ?>
		@foreach($credits as $credit)
		<tr>
			<td>{{ $credit->id }}</td>
			<td>{{ $users->find($credit->user_id)->document }} </td>
			<td>{{ $users->find($credit->user_id)->name }}  {{$users->find($credit->user_id)->lastname }}</td>
			<td>{{ $credit->fecha_desembolso }} ({{ $ultpagos[$credit->id] }})</td>
			<td align="right">${{ $credit->valor_desembolso }}</td>
			<td align="right">{{ $credit->tasa_mensual }}% ({{ $credit->cuotas }})</td>
			<td align="right">${{ $mora[$credit->id] }}</td>
			<td align="right">${{ $intereses[$credit->id] }}</td>
			<td align="right">${{ $credit->saldo_capital}}</td>		
		</tr>
		<?php 	$salcap=$salcap+$credit->saldo_capital; 
				$salint=$salint+$intereses[$credit->id] ?>
		@endforeach
	</tbody>
	</tr>
 </table>
 
 {!! Form::label('milabel','Capital por Cobrar: '.$salcap.' '.'Interes por cobrar: '.$salint.' Mora'.$mporcobrar) !!}
@endsection

@section('contenido2')

<br>
@endsection



