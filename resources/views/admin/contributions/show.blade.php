@extends('admin/template/main')
@section('title', 'Aportes')
@section('entidad') 
 Imprimir Aporte
@endsection


@section('boton')
	<p align="right">
 		{{--<a href="{{ route ('home') }}" class="print-link no-print btn btn-primary">Continuar</a>--}}
 		<a href="{{ url()->previous() }}" class="no-print btn btn-primary">Volver</a>
 		<button class="print-link no-print btn btn-info" onclick="jQuery('#ele1').print({
 			globalStyles: true,
 			title:null,
 			timeout: 20000})"> <img src="{{ URL::asset('img/imp.png') }}" class="admin-logo-nav" alt="profile Pic" height="20" width="20" >Imprimir</button>
 	</p>
@endsection

@section('contenido')

<div class="container table-responsive" style="width:80%">
	@section('title2')
	<a allign="center"> <img src="{{ URL::asset('img/logo.png') }}" class="admin-logo-nav " alt="profile Pic" height="25" width="25"> </a> Fondo Gomoa
	@endsection
       <table  class="table table-hover table-striped 
          table-condensed tasks-table table-responsive"  >
		<tr>
			<thead>
				<th width="50%">Aporte</th>
				<th width="50%" class="text-center">{{$contribution->id}}</th>
			</thead>
			<tbody>
				<tr>
				<td style="width:10%"> Cedula Socio </td>
				<td style="width:20%" align="right">{{ $user->document }}</td>
				</tr>
				<tr>
				<td style="width:10%"> Nombre </td>
				<td style="width:20%" align="right">{!! $user->name !!}  {!! $user->lastname !!}</td>
				</tr>
				<tr>
				<td style="width:10%"> Fecha Aporte </td>
				<td style="width:20%" align="right">{{ $contribution->created_at }}</td>
				</tr>
				<tr>
				<td style="width:15%"> Concepto </td>
				<td style="width:20%" align="right"><strong>{!! $concept->concept !!}</strong></td>
				</tr>
				<tr>
				<td style="width:15%"> Valor </td>
				<td style="width:20%" align="right"><strong>${{ number_format($contribution->amount) }}</strong></td>
				</tr>
				<tr>
				<td style="width:10%"> Elaboró </td>
				<td style="width:20%" align="right">{!! $contribution->user_create !!}</td>
				</tr>
			</tbody>
		</tr>
	</table>
</div>
@endsection