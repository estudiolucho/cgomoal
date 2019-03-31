@extends('admin/template/main')
@section('title', 'Aportes')
@section('entidad') 
 Concepto de Aporte
@endsection
@section('boton')
	<p align="right">
 	<a href="{{ route ('cconcept.index') }}" class="btn btn-info">Ir a Conceptos de  Aporte</a>
 	</p>
@endsection
@section('contenido')

	<div class="container" 	>
		{!! Form::open(['route' => 'cconcept.store', 'method'=>'POST']) !!}
	 	<div class="form-group">
	 		{!! Form::label('concept','Concepto') !!}
	 		{!! Form::input('text','concept',null,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'concepto') ) !!}
	 	</div>
	 	<div class="form-group">
	 		{!! Form::label('description','Descripcion') !!}
	 		{!! Form::text('description',null,['class'=>'form-control','placeholder'=>'descripcion de concepto']) !!}
	 	</div>
	 	<div class="form-group">
	 		{!! Form::label('active','Estado') !!}
	 		{!! Form::checkbox('active',1,true) !!}
	 	</div>
	 </div>
	
 @endsection

 @section('contenido2')
	 <div class="container" >
	  	<div class="form-group">
	 		{!! Form::submit('Crear',['class'=> 'btn btn-primary']) !!}
	 	</div>
	 </div>

 	{!! Form::close() !!}

@endsection