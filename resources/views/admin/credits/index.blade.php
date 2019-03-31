{{-- {dd($credits)--}}
@extends('admin/template/main')
@section('title', 'Creditos')
@section('entidad') 
 Lista de Creditos Activos 
@endsection
@section('boton')
<p align="center">
 	<a href="{{ route ('credit.create') }}" class="btn btn-secondary text-primary">Nuevo Credito <img src="{{ URL::asset('img/cred.png') }}" class="admin-logo-nav" title="Nuevo Credito" alt="profile Pic" height="25" width="25" ></a>
 	</p>
 @endsection
@section('contenido') 
<table  class="table table-striped table-sm"border="1">
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
			<td align="right">${{ number_format($credit->saldo_capital,2) }}</td>
			<td>
				<a href="{{ route('payment.createbycredit',$credit->id) }}" class="btn btn-sm btn-secondary text-success"><img src="{{ URL::asset('img/pay.png') }}" class="admin-logo-nav" alt="profile Pic" title="Pagar" height="20" width="20"></a>
				@if(Auth::user()->role_id === 1 )
			  	<a href="{{ route('credit.edit',$credit->id) }}" class="btn btn-sm btn-secondary text-danger" ><img src="{{ URL::asset('img/edit.png') }}" title="Editar" class="admin-logo-nav" alt="profile Pic" height="20" width="20" ></a>
			  	@endif
			  	{{--<a href="{{ route('payment.list',['credit' => Crypt::encrypt($credit->id) ]) }}" class="btn btn-sm btn-secondary text-primary">Pagos</a>--}}
			  	<a href="{{ route('payment.list',['credit' => $credit->id ]) }}" class="btn btn-sm btn-secondary text-primary"><img src="{{ URL::asset('img/view.png') }}" title="Ver" class="admin-logo-nav" alt="profile Pic" height="20" width="20"></a>
			</td>		
		</tr>
		<?php $salcap=$salcap+$credit->saldo_capital; 
				$salint=$salint+$credit->saldo_interes ?>
		@endforeach
	</tbody>
	</tr>
 </table>
 {!! $credits->render() !!} 
 {!! Form::label('concept_id','Capital por Cobrar: '.$salcap) !!}
@endsection
@section('contenido2')
<p align="center" >
<a href="{{ route ('credit.generaInt') }}" class="btn btn-info">Ver Interes por Cobrar</a>
<a href="{{ route ('credit.canceled') }}" type="button" class="btn btn-default">Ver Creditos Cancelados</a>
 	</p>

@endsection