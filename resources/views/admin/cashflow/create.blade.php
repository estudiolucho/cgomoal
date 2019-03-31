
@extends('admin/template/main')
@section('title', 'Flujo de Caja')
@section('entidad') 
 	Registro de Flujo de Caja
@endsection
@section('boton')
	{{--<p align="right">
 	<a href="{{ route ('expense.index') }}" class="btn btn-primary">Ir a Gastos</a>
 	</p>--}}
 @endsection
@section('contenido')

	<div class="container" >
	date
	amount
	concept
	type
	balance
	description
	user_create
	user_update
	created_at
	updated_at
	
		{!! Form::open(['route' => 'cashflow.store', 'method'=>'POST']) !!}
	 	<div class="form-group">
	 		{!! Form::label('concept_id','Concepto') !!}
	 		{!! Form::select('concept', array(null =>'Seleccione Concepto') + array('ajuste_ent' =>'Ajuste de Entrada','ajuste_sal'=>'Ajuste de Salida','gasto'=>'Gasto'),null,array('required' => 'required') ) !!}	
	 	</div>
	 	<div class="form-group">
	 		{!! Form::label('concept_id','Tipo') !!}
	 		{!! Form::select('type', array(null =>'Seleccione Tipo','entrada' =>'Entrada','salida'=>'Salida'),null,array('required' => 'required') ) !!}	
	 	</div>
	 	<div class="form-group">
	 		{!! Form::label('description','Fecha') !!}
	 		{!! Form::date('date', \Carbon\Carbon::now()) !!} 		
	 	</div>
	 	<div class="form-group">
	 		{!! Form::label('cantidad','Valor') !!}
	 		{!! Form::number('amount', null,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'valor','step'=>'0.1')) !!}
	 	</div>
	 	<div class="form-group">
	 		{!! Form::label('descripcion','Detalle') !!}
	 		{!! Form::text('description',null,['class'=>'form-control','placeholder'=>'detalle del movimiento','required']) !!}
	 	</div>
	 </div>
 @endsection

 @section('contenido2')

 	<div class="form-group container">
 		{!! Form::submit('Crear',['class'=> 'btn btn-primary']) !!}
 	</div>

 	{!! Form::close() !!}
@endsection
