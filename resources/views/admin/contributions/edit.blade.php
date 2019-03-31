@extends('admin/template/main')
@section('title', 'Aportes')
@section('entidad') 
 	Editar Aporte {!! $contribution->id !!}
@endsection
@section('boton')
	<p align="right">
 	<a href="{{ route ('contribution.index') }}" class="btn btn-primary">Ir a Aportes</a>
 	</p>
 @endsection
@section('contenido')
	falta hacer las pruebas suficientes
	{!! Form::open(['route' => ['contribution.update',$contribution], 'method'=>'PUT']) !!}
	como paso el id de contribution_concepts al update?
 	<div class="form-group">
 		{!! Form::label('concept_id','Concepto') !!}
 		{!! Form::select('concept_id',array( null =>'Seleccione') + array_pluck($contribution_concepts,'concept','id'),['required'=>'required']) !!}
 		{!! Form::input('text','concept_id',$contribution->concept_id,['readonly','size'=>'1']) !!}
 	</div>
 	como paso las cedulas al select y luego  al update?
 	<div class="form-group">
 		{!! Form::label('user_id','usuario') !!}
 		{{--{!! Form::select('user_id', array( null =>'Seleccione') + array_pluck($users,'document','id')) !!}--}}
 		{!! Form::input('text','user_document',$user->document,['readonly','size'=>'9']) !!}
 		{!! Form::input('text','user_id',$contribution->user_id,['readonly','size'=>'1','hidden']) !!}	
 	</div>
 	<div class="form-group">
 		{!! Form::label('contribution_date','Fecha de Pago') !!}
 		{!! Form::date('contribution_date', \Carbon\Carbon::parse($contribution->contribution_date) ) !!} 		
 	</div>
 	<div class="form-group">
 		{{--
 		{!! Form::label('cantidad','Valor') !!}
 		{!! Form::input('text','amount', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'valor del aporte')) !!}
 		--}}
 		<div class="input-group"> 
	 		{!! Form::label('cantidad','Valor Aporte :') !!}
	        <span class="input-group-addon" >$</span>
	        {!! Form::number('amount', $contribution->amount ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'valor del aporte')) !!}
        </div>
 	</div>
 	<div class="form-group">
 		{!! Form::label('descripcion','Detalle') !!}
 		{!! Form::input('text','description',$contribution->description , array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'detalle','size'=> 80) ) !!}
 	</div>
 @endsection

 @section('contenido2')
 	<div class="form-group">
 		{!! Form::submit('Actualizar',['class'=> 'btn btn-primary']) !!}
 	</div>
 	{!! Form::close() !!}
@endsection