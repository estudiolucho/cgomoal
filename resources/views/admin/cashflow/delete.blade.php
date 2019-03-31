@extends('admin/template/main')
@section('title', 'Gastos')
@section('entidad') 
 	Eliminar ultimo Credito y Flujo de Caja
@endsection
@section('boton')
	{{--<p align="right">
 	<a href="{{ route ('cashflow.edit') }}" class="btn btn-primary">Ir a Gastos</a>
 	</p>--}}
 @endsection

@section('contenido')

	{!! Form::open(['route' => 'cashflow.search', 'method'=>'GET']) !!}
	<table  class="table table-striped table-responsive table-sm" >
	<tr><td width="250px"><div class="form-group">
 		{!! Form::label('cantidad','Consecutivo Flujo de Caja') !!}</td><td width="250px">
 		{!! Form::number('cashflow_id', null,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'consecutivo')) !!}
 	</div></td></tr>
 	<tr><td><div class="form-group">
 		{!! Form::label('cantidad','Consecutivo Credito',['class' => 
    'form-label','style'=>'width:130 px']) !!}</td><td>
 		{!! Form::number('credit_id', null,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'consecutivo')) !!}
 	</div></td></tr>
 	</table>

@endsection
@section('contenido2')
	<div class="container" >
 	<ul class="table-info"><a> {!! Form::label('credito','¡Atencion!: Consecutivos de Flujo de Caja y Credito no quedaran en los reportes, enviar correo notificando a los interesados') !!}</a> </ul>
 	<ul><li><a class="table-danger"> {!! Form::label('credito','Solo ultimo Flujo de Caja se puede eliminar') !!} </a></li></ul>
 	<ul><li><a class="table-danger"> {!! Form::label('credito','Esta accion es Irreversible' ) !!} </a></li></ul>
 
 	<div class="form-group ">
 		{!! Form::submit('Buscar',['class'=> 'btn btn-primary']) !!}
 	</div>

 	{!! Form::close() !!}
@endsection