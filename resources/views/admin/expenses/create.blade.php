
@extends('admin/template/main')
@section('title', 'Gastos')
@section('entidad') 
 	Registrar Gasto
@endsection
@section('boton')
	<p align="right">
 	<a href="{{ route ('expense.index') }}" class="btn btn-primary">Ir a Gastos</a>
 	</p>
 @endsection
@section('contenido')

<div class="container" 	>
	{!! Form::open(['route' => 'expense.store', 'method'=>'POST']) !!}
 	<div class="form-group">
 		{!! Form::label('concept_id','Concepto') !!}
 		{!! Form::select('concept_id', array('' =>'Seleccione Concepto') + array_pluck($expense_concepts,'concept','id'),array('required') ) !!}	
 	</div>
 	<div class="form-group">
 		{!! Form::label('description','Fecha ') !!}
 		{!! Form::date('expense_date', \Carbon\Carbon::now(),['readonly']) !!} 		
 	</div>
 	<div class="form-group">
 		{!! Form::label('cantidad','Valor') !!}
 		{!! Form::number('amount', null,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'valor','min'=>'0')) !!}
 	</div>
 	<div class="form-group">
 		{!! Form::label('descripcion','Detalle') !!}
 		{!! Form::text('description',null,['class'=>'form-control','placeholder'=>'detalle del gasto','required']) !!}
 		
 		


 	</div>
</div>
 @endsection

 @section('contenido2')
	 <div class="container" 	>
	 	<div class="form-group">
	 		{!! Form::submit('Crear',['class'=> 'btn btn-primary']) !!}
	 	</div>
	 </div>

 	{!! Form::close() !!}
@endsection
