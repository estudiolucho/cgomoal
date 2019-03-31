@extends('admin/template/main')
@section('title', 'Pagos')
@section('entidad') 
 Pagos Realizados
 <h2>Credito {{ $id }} </h2>
@endsection
@section('boton')
	{{--  EN AWS HACE QUE SE CIERRE LA SESION
 	<a href="{{ route('payment.posfindcredit',Crypt::encrypt($user->document)) }}" class="no-print btn btn-primary">Ver Creditos</a>
 	--}}
 	<a href="{{ route('payment.posfindcredit',$user->document) }}" class="no-print btn btn-primary">Ver Creditos {!! $user->document!!}</a>
 	<button class="print-link no-print btn btn-info" onclick="jQuery('#ele4').print({
 			globalStyles: true,
 			title:null,
 			timeout: 20000})"><img src="{{ URL::asset('img/imp.png') }}" class="admin-logo-nav" alt="profile Pic" height="25" width="25" > Imprimir</button>
 	</p>
 	<p align="right"></p>
@endsection
@section('contenido') 
	
<table  class="table table-striped table-sm" border="1">
	<tr>
	<thead>
		<th>Rec#</th>
		<th>Fecha aplicacion Pago</th>
		<th>Fecha realizacion Pago</th>
		<th>Valor Pagado</th>
		<th>Interes Mora</th>
		<th>Saldo Mora</th>
		<th>Abono Int</th>
		<th>Abono Capital</th>
		<th>Saldo Int</th>
		<th>Saldo Capital</th>
		<th>Descripcion</th>
		<th>Elaboró</th>
		<th class="no-print ">Acciones</th>
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
			<td>{{ $payment->user_create }} </td>
			<td class="no-print">
				<a href="{{ route('payment.show',$payment->id) }}" class="no-print text-info"><img src="{{ URL::asset('img/view.png') }}" class="admin-logo-nav" title="Ver Detalle" alt="profile Pic" height="20" width="20"></a>
				@if(Auth::user()->role_id === 1 )
			  	<a href="{{ route('payment.edit',$payment->id) }}" class="no-print  text-danger "><img src="{{ URL::asset('img/edit.png') }}" class="admin-logo-nav" itle="Editar Pago" alt="profile Pic" height="20" width="20" ></a>
			  	@endif
			</td>		
		</tr>
		<?php 	 
				$totintmora=$totintmora+$payment->intmora;
				$totaboint=$totaboint+$payment->abono_interes;
				$totabocap=$totabocap+$payment->abono_capital ?>
		@endforeach
		<?php $totabonos=$totintmora+$totaboint+$totabocap; 
		?>
	</tbody>
	</tr>
 </table>
 <ul><li><a class="table-info"> 
 {!! Form::label('totales','Total Abonos: '.$totabonos.' '.'Total Mora Pagada: '.$totintmora.' '.'Total Interes Pagado: '.$totaboint.' '.' Total Capital Pagado: '.$totabocap) !!}
 </a></li></ul>
@endsection