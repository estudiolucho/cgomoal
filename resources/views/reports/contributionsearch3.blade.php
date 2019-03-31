@extends('admin/template/main')
@section('title', 'Reportes')
@section('entidad') 
 	Buscar Aportes por Fecha-Socio
@endsection
@section('boton')
	<p align="right">
 	<a href="{{ route ('contribution.index') }}" class="btn btn-primary">Ir a Aportes</a>
 	</p>
 @endsection
@section('contenido')
	{!! Form::open(['route' => 'reports.contributions.listbydocument', 'method'=>'POST']) !!}
 	<div class="form-group">
 		{!! Form::label('user_id','Socio') !!}
 		{!! Form::select('user_id', array( null =>'Seleccione') + array_pluck($users,'document','id')) !!}	
 	</div>
 	<div class="form-group">
 		{!! Form::label('fini','Fecha inicial') !!}
 		{!! Form::date('startdate', \Carbon\Carbon::now()) !!} 
 		{!! Form::label('ffin','Fecha final') !!}
 		{!! Form::date('enddate', \Carbon\Carbon::now()->addDay()) !!} 
 	</div>
 @endsection

 @section('contenido2')
 	<div class="form-group">
 		{!! Form::submit('Buscar',['class'=> 'btn btn-primary']) !!}
 	</div>
 	{!! Form::close() !!}
@endsection