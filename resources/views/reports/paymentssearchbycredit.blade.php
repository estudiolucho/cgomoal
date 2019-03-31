@extends('admin/template/main')
@section('title', 'Reportes')
@section('entidad') 
Reportes de Pagos por credito
@endsection

@section('contenido') 
 	
 	{!! Form::open(['route' => 'payment.inputCredit', 'method'=>'POST']) !!}
 	{{--
 	<div class="form-group">
 		{!! Form::label('identificacion','Numero de Identificacion') !!}
 		{!! Form::input('text','document','',['size'=>'10','placeholder'=>'ingrese documento']) !!}
 	</div>
 	--}}
 	<div>
 		{!! Form::label('credit','Numero de Credito') !!}
 		{!! Form::input('text','credit_id','2',['size'=>'2','required']) !!}
 	</div>
 	pendiente hacer la busqueda para listar los pagos
 @endsection
 @section('contenido2')	
 	<div class="form-group">
 		{!! Form::submit('Ver Pagos',['class'=> 'btn btn-primary']) !!}
 	</div>

 	{!! Form::close() !!}
@endsection