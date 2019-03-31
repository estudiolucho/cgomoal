{{-- dd($cramount,$crmonths,$crintrate,$paymentamount,$per,$salini,$pint,$pcap,$pcuota,$salfin) --}}
@extends('admin/template/main')
@section('title', 'Creditos')
@section('entidad') 
 Tabla de Amortizacion 
@endsection
@section('boton')
<p align="center">
 	<a href="{{ route ('credit.simulator') }}" class="print-link no-print btn btn-info ">Volver</a>
 	<button class="print-link no-print btn btn-info" onclick="jQuery('#ele4').print({
 			globalStyles: true,
 			title:null,
 			timeout: 20000})"> Imprimir</button>
 	</p>
 <p></p>
 @endsection
 @section('title2')
 <caption align="center" class="caption"> Tabla de Amortizacion</caption>
 @endsection
@section('contenido') 
<table  style="width:80%" class="table table-bordered table-condensed table-sm" 	>
	<thead>
		<th class="text-center" style="width:10%">Valor Prestado</th>
		<th class="text-center" style="width:10%">Tasa de Interes</th>
		<th class="text-center" style="width:10%">Cuotas Pactadas</th>
		<th class="text-center" style="width:10%">Cuota Mensual</th>
	</thead>
	<tbody>
		<tr>
			<td class="text-center">${{ $cramount }}</td>
			<td class="text-center">{{ $crintpercent }}%</td>
			<td class="text-center">{{ $crmonths }}</td>
			<td class="text-center"><strong> ${{ $paymentamount }}</strong></td>

		</tr>
		
	</tbody>

</table>
<table style="width:80%" class="table table-striped table-condensed table-sm" border="1">
	<tr>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.btnPrint').printPage();

		});
	</script>
	<thead>
		<th class="text-center" style="width:2%">#Cuota</th>
		<th class="text-center" style="width:10%">Saldo Inicial</th>
		<th class="text-center" style="width:10%">Interes</th>
		<th class="text-center" style="width:10%">Capital</th>
		<th class="text-center" style="width:10%">Couta</th>
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