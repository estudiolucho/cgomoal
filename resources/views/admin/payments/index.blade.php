@extends('admin/template/main')
@section('title', 'Pagos')
@section('entidad') 
 Modulo de Pagos
@endsection

@section('contenido') 
	<div class="container" >
	 	{!! Form::open(['route' => 'payment.findcredit', 'method'=>'POST']) !!}
	 	<div class="form-group">
	 		{!! Form::label('identificacion','Numero de Identificacion') !!}
	 		{!! Form::input('text','document','',['size'=>'10','placeholder'=>'ingrese documento','required' => 'required']) !!}
	 	</div>
	 </div>
@endsection
@section('contenido2')	

 	<div class="form-group container">
 		{!! Form::submit('Buscar',['class'=> 'btn btn-primary']) !!}
 	</div>
 	{!! Form::close() !!}
@endsection