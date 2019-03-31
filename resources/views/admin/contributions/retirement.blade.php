@extends('admin/template/main')
@section('title', 'Aportes')
@section('entidad') 
 	Retiro de Aportes
@endsection
@section('boton')
	
 @endsection
@section('contenido')
	{!! Form::open(['route' => 'contribution.storeretirement', 'method'=>'POST']) !!}
 	<div class="form-group text-danger">
 		{!! Form::label('concept_id','Concepto') !!}
 		{!! Form::select('concept_id',array( null =>'Seleccione') + array_pluck($contribution_concepts,'concept','id'),['required'=>'required']) !!}
 	</div>
 	<div class="form-group text-danger">
 		{!! Form::label('user_id','usuario') !!}
 		{!! Form::select('user_id', array( null =>'Seleccione') + array_pluck($users,'document','id')) !!}	
 	</div>
 	<div class="form-group text-danger">
 		{!! Form::label('contribution_date','Fecha de Pago') !!}
 		{!! Form::date('contribution_date', \Carbon\Carbon::now()) !!} 		
 	</div>
 	<div class="form-group text-danger" >
 		<div class="input-group"> 
	 		{!! Form::label('cantidad','Valor :') !!}
	        <span class="input-group-addon" >$</span>
	        {!! Form::number('amount', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'valor del aporte','min'=>'0')) !!}
        </div>
 	</div>
 	<div class="form-group text-danger">
 		{!! Form::label('descripcion','Detalle') !!}
 		{!! Form::input('text','description',null , array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'detalle','size'=> 40) ) !!}

 		{{-- !! Form::text('description',null ,['class'=>'form-control','placeholder'=>'detalle','required']) !!--}}
 	</div>
 @endsection

 @section('contenido2')
 	<div class="form-group">
 		{!! Form::submit('Crear',['class'=> 'btn btn-primary']) !!}
 	</div>
 	{!! Form::close() !!}
@endsection