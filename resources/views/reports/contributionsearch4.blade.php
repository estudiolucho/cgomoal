@extends('admin/template/main')
@section('title', 'Reportes')
@section('entidad') 
 	Buscar Aportes por Socio
@endsection
@section('boton')
	<p align="right">
 	<a href="{{ route ('contribution.index') }}" class="btn btn-primary">Ir a Aportes</a>
 	</p>
 @endsection
@section('contenido')
	{!! Form::open(['route' => 'reports.contributions.listdocument', 'method'=>'POST']) !!}
 	<div class="form-group">
 		{!! Form::label('user_id','Socio') !!}
 		{!! Form::select('user_id', array( null =>'Seleccione') + array_pluck($users,'document','id')) !!}	
 	</div>
 @endsection

 @section('contenido2')
 	<div class="form-group">
 		{!! Form::submit('Buscar',['class'=> 'btn btn-primary']) !!}
 	</div>
 	{!! Form::close() !!}
@endsection