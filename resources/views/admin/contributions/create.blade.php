@extends('admin/template/main')
@section('title', 'Aportes')
@section('entidad') 
 	Registrar Aporte
@endsection
@section('boton')
	<p align="right">
 	<a href="{{ route ('contribution.index') }}" class="btn btn-primary">Ir a Aportes</a>
 	</p>
 @endsection
@section('contenido')
<div class="container" 	>
	{!! Form::open(['route' => 'contribution.store', 'method'=>'POST']) !!}
 	<div class="form-group">
 		{!! Form::label('concept_id','Concepto') !!}
 		{!! Form::select('concept_id',array( null =>'Seleccione') + array_pluck($contribution_concepts,'concept','id'),['required'=>'required']) !!}
 	</div>
 	<div class="form-group">
 		{!! Form::label('user_id','Usuario') !!}
 		{!! Form::select('user_id', array( null =>'Seleccione') + array_pluck($users,'document','id')) !!}	
 	</div>
 	<div class="form-group">
 		{!! Form::label('contribution_date','Fecha de Pago') !!}
 		{!! Form::date('contribution_date', \Carbon\Carbon::now(),['readonly']) !!} 		
 	</div>
 	<div class="form-group">
 		{{--
 		{!! Form::label('cantidad','Valor') !!}
 		{!! Form::input('text','amount', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'valor del aporte')) !!}
 		--}}
 		<div class="input-group"> 
	 		{!! Form::label('cantidad','Valor Aporte :') !!}
	        <span class="input-group-addon" >$</span>
	        {!! Form::number('amount', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'valor del aporte','min'=>'0')) !!}
        </div>
 	</div>
 	<div class="form-group">
 		{!! Form::label('descripcion','Detalle') !!}
 		{!! Form::input('text','description',null , array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'detalle','size'=> 80) ) !!}

 		{{-- !! Form::text('description',null ,['class'=>'form-control','placeholder'=>'detalle','required']) !!--}}
 	</div>
</div>
 @endsection

 @section('contenido2')
 	<div class="container" 	>
	 	<div class="form-group">
	 		{!! Form::submit('Crear',['class'=> 'btn btn-primary']) !!}
	 	</div>
	 	{!! Form::close() !!}
 	</div>

@endsection