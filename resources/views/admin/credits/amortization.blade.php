{{-- dd($cramount,$crmonths,$crintrate,$paymentamount,$per,$salini,$pint,$pcap,$pcuota,$salfin) --}}
@extends('admin/template/main')
@section('title', 'Creditos')
@section('entidad') 
 Tabla de Amortizacion 
@endsection
@section('boton')
	<p align="right">
 	<a href="{{ route ('credit.create') }}" class="btn btn-default no-print">Cancelar</a>
 	<button class="print-link no-print btn btn-info" onclick="jQuery('#ele4').print({
 			globalStyles: true,
 			title:null,
 			timeout: 20000})">Imprimir</button>
 	</p>
 
 @endsection
 @section('herramientas')
 {!! Form::open(['route' => 'credit.confirm', 'method'=>'POST']) !!}
 	{!! Form::input('text','user_id', $user->id,['hidden']) !!} 
 	{!! Form::input('text','valor_desembolso', $cramount,array('hidden')) !!}
 	{!! Form::date('fecha_desembolso', $crdate,['hidden']) !!} 
 	{!! Form::input('text','tasa_mensual', $crintpercent,array('hidden')) !!}
 	{!! Form::input('text','cuotas', $crmonths ,array('hidden')) !!}
 	{!! Form::input('text','valor_cuota', $paymentamount ,array('hidden')) !!} 
 	{!! Form::text('descripcion',null,['hidden']) !!} 
 	
 	<div align="right" class="form-group no-print">
 		{!! Form::submit('Crear Credito',['class'=> 'btn btn-warning']) !!}
 	</div>
	
{!! Form::close() !!}
 @endsection
@section('contenido') 
<table  style="width:80%" class="table table-bordered table-condensed" 	>
	<thead>
		<th class="text-center" style="width:10%">#Cedula</th>
		<th class="text-center" style="width:10%">Fecha de Desembolso</th>
		<th class="text-center" style="width:10%">Valor Prestado</th>
		<th class="text-center" style="width:10%">Tasa de Interes</th>
		<th class="text-center" style="width:10%">Cuotas Pactadas</th>
		<th class="text-center" style="width:10%">Cuota Mensual</th>
	</thead>
	<tbody>
		<tr>
			<td class="text-center" >{{ $user->document }}</td>
			<td class="text-center">{{ $crdate }}</td>
			<td class="text-center">${{ $cramount }}</td>
			<td class="text-center">{{ $crintpercent }}%</td>
			<td class="text-center">{{ $crmonths }}</td>
			<td class="text-center"><strong> ${{ $paymentamount }}</strong></td>

		</tr>
		
	</tbody>

</table>
<table style="width:80%" class="table table-striped table-condensed " border="1">
	<tr>
	{{--
		<colgroup>
            <col class="col-md-1">
            <col class="col-md-2">
        </colgroup>
        --}}
	<thead>
		<th class="text-center" style="width:1%">#Cuota</th>
		<th class="text-center" style="width:10%">Saldo Inicial</th>
		<th class="text-center" style="width:10%">Interes</th>
		<th class="text-center" style="width:10%">Capital</th>
		<th class="text-center" style="width:10%">Cuota</th>
		<th class="text-center" style="width:10%">Saldo Final</th>
	</thead>
	<tbody>
		@foreach($per as $p)
		<tr>
			<td align="right">{{ $p }}</td>
			<td align="right">${{ number_format($salini[$p]) }} </td>
			<td align="right">${{ number_format($pint[$p]) }}</td>
			<td align="right">${{ number_format($pcap[$p]) }}</td>
			<td align="right">${{ number_format($pcuota[$p]) }}</td>
			<td align="right">${{ number_format($salfin[$p])}}</td>
		</tr>
		@endforeach
	</tbody>
	</tr>
 </table>
 @endsection
@section('contenido2')

@endsection