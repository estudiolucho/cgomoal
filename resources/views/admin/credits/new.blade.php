@extends('admin/template/main')
@section('title', 'Creditos')
@section('entidad') 
 Nuevo  Credito
@endsection

@section('contenido') 
 	{!! Form::open(['route' => 'credit.findUser', 'method'=>'POST']) !!}
 	<div class="form-group">
 		{!! Form::label('identificacion','Numero de Identificacion') !!}
 		{!! Form::input('text','document','',['size'=>'10','placeholder'=>'ingrese documento']) !!}
 	</div>
 @endsection
 @section('contenido2')	
 	<div class="form-group">
 		{!! Form::submit('Buscar',['class'=> 'btn btn-primary']) !!}
 	</div>
 	{!! Form::close() !!}
@endsection