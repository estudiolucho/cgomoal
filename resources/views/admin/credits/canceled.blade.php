{{-- esta vista es igual a la de indice de creditos "index"--}}
@extends('admin/template/main')
@section('title', 'Creditos')
@section('entidad') 
 Creditos Cancelados 
@endsection
@section('contenido') 
<table  class="table table-striped table-sm" border="1">
	<tr>
	<thead>
		<th>Num</th>
		<th>Cliente</th>
		<th>Nombres y Apellidos</th>
		<th>Fecha Desembolso</th>
		<th>Valor Desembolso</th>
		<th>Cuotas / Valor</th>
		{{--<th>Saldo Interes</th>--}}
		<th>Saldo Capital</th>
		<th>Acciones</th>
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
			<td>{{ $credit->fecha_desembolso }}</td>
			<td align="right">${{ number_format($credit->valor_desembolso) }}</td>
			<td align="right">{{ $credit->cuotas }} / ${{ number_format($credit->valor_cuota,1) }}</td>
			{{--<td>{{ $credit->saldo_interes }}</td>--}}
			<td align="right">${{ number_format($credit->saldo_capital) }}</td>
			<td>
			  	{{--<a href="{{ route('payment.list',['credit' => Crypt::encrypt($credit->id) ]) }}" class="btn btn-secondary text-primary">Pagos</a>--}}
			  	<a href="{{ route('payment.list',['credit' => $credit->id ]) }}" class="btn btn-secondary text-primary">Pagos</a>
			</td>		
		</tr>
		<?php $salcap=$salcap+$credit->saldo_capital; 
				$salint=$salint+$credit->saldo_interes ?>
		@endforeach
	</tbody>
	</tr>
 </table>
 {!! Form::label('concept_id','Capital por Cobrar: '.$salcap) !!}
@endsection
@section('contenido2')
@endsection