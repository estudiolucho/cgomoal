@extends('admin/template/main')
@section('title', 'Concepto de Gasto')
@section('entidad') 
 Concepto de Gasto
@endsection
@section('contenido')
	<div class="container" >
		{!! Form::open(['route' => 'econcept.store', 'method'=>'POST']) !!}
	 	<div class="form-group">
	 		{!! Form::label('concept','Concepto') !!}
	 		{!! Form::input('text','concept',null,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Nombre de Concepto') ) !!}
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