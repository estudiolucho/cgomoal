@extends('admin/template/main')
@section('title', 'Creditos')
@section('entidad') 
 Modulo de Pagos
 <h3>creditos activos {{ $users->name}} {{ $users->lastname}} </h3>
@endsection
@section('boton')
	<div lass="form-group">
	<p align="right">
	@if(Auth::user()->role_id === 1 or Auth::user()->role_id === 2)
 	<a href="{{ route ('payment.index') }}" class="btn btn-primary">Ir a Pagos</a>
 	<a href="{{ route ('user.index') }}" class="btn btn-primary">Ir a Usuarios</a>
 	</p>
 	<p align="right">
 	
 	@endif
 	</p>
 	</div>
@endsection
@section('contenido')
<table  class="table table-striped table-sm" border="1">
	<tr>
	<thead>
		<th>Credito #</th>
		<th>Cliente</th>
		<th>Fecha Desembolso</th>
		<th>Valor Desembolso</th>
		<th>Tasa  Cuotas </th>
		<th> VrCuota</th>
		<th>Saldo Rec. Mora</th>
		<th>Saldo Interes</th>
		<th>Saldo Capital</th>
		<th>Pago Total</th>
		<th>Acciones</th>
	</thead>
	<tbody>
		@foreach($credits as $credit)
		<tr>
			<td>{{ $credit->id }}</td>
			<td>{{ $users->find($credit->user_id)->document }} </td>
			{{--<td>{{ $users->find($credit->user_id)->name }}  {{$users->find($credit->user_id)->lastname }}</td>--}}
			<td>{{ $credit->fecha_desembolso }}</td>
			<td align="right">${{ number_format($credit->valor_desembolso) }}</td>
			<td>{{ $credit->tasa_mensual}}% / {{ $credit->cuotas }}</td>
			<td >{{ $credit->valor_cuota }}</td>
			<td align="right">${{ number_format($mora[$credit->id],2) }}</td>
			<td align="right">${{ number_format($int[$credit->id],2) }}</td>
			<td align="right">${{ number_format($credit->saldo_capital,2) }}</td>
			<td align="right"><strong>${{ number_format($mora[$credit->id]+$int[$credit->id]+$credit->saldo_capital,2) }}</strong></td>
			<td>
				@if(Auth::user()->role_id === 1 or Auth::user()->role_id === 2)
				<a href="{{ route('payment.createbycredit',$credit->id) }}" class=" no-print
				 text-success "><img src="{{ URL::asset('img/pay.png') }}" title="Pagar Cuota" class="admin-logo-nav" alt="profile Pic" height="30" width="30"> </a>
				@endif
				<a href="{{ route('payment.list',['credit' => $credit->id ]) }}" class=" text-info"><img src="{{ URL::asset('img/view.png') }}" class="admin-logo-nav" title="Ver Pagos"alt="profile Pic" height="30" width="30"></a>
				{{--<a href="{{ route('payment.list',['credit' => Crypt::encrypt($credit->id) ]) }}" class=" text-info">Ver</a>--}}

			</td>		
		</tr>
		@endforeach
	</tbody>
	</tr>
 </table>
@endsection
@section('contenido2')
<ul class="table-info"><a> {!! Form::label('info1','¡Atencion!: Saldo en Mora y Saldo Interes  incluyen valores que hayan quedado pendientes en el ultimo pago') !!}</a>
<a class="table-danger"> {!! Form::label('info2','Para Pago Total de credito sumar saldo en mora+saldo interes+saldo capital') !!} </a> </ul>

@endsection