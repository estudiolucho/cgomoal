@extends('admin/template/main')
@section('title', 'Gastos')
@section('entidad') 
 	Registro de Flujo de Caja
@endsection
@section('boton')

 @endsection

@section('contenido')

	{!! Form::open(['route' => 'cashflow.editar', 'method'=>'GET']) !!}
	<div class="form-group">
 		{!! Form::label('cantidad','Numero de Registro') !!}
 		{!! Form::number('id', null,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'consecutivo')) !!}
 	</div>

@endsection
@section('contenido2')

 	<div class="form-group ">
 		{!! Form::submit('Buscar',['class'=> 'btn btn-primary']) !!}
 	</div>

 	{!! Form::close() !!}
@endsection
