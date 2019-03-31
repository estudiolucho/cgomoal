{{-- dd($expense)--}}

@extends('admin/template/main')
@section('title', 'Gastos')
@section('entidad') 
 	Editar Gasto
@endsection
@section('boton')
	<p align="right">
 	<a href="{{ route ('expense.index') }}" class="btn btn-primary">Ir a Gastos</a>
 	</p>
 @endsection
@section('contenido')
	{!! Form::open(['route' => ['expense.update',$expense], 'method'=>'PUT']) !!}
	como paso el id de expence_concepts al update?
 	<div class="form-group">
 		{!! Form::label('concept_id','Concepto') !!}
 		{!! Form::select('concept_id_desc', [$expense_concepts->find($expense->concept_id)->concept]) !!}	
 		{{--
 		{!! Form::label('concept_id',$expense->concept_id) !!} 
 		--}}
 		{!! Form::input('text','concept_id',$expense->concept_id,['readonly','size'=>'1']) !!}
 		</div>
 	<div class="form-group">
 		{!! Form::label('description','Fecha de Pago') !!}
 		{!! Form::date('expense_date', \Carbon\Carbon::parse($expense->expense_date)) !!} 		
 	</div>
 	<div class="form-group">
 		{!! Form::label('cantidad','Valor') !!}
 		{!! Form::input('text','amount', $expense->amount,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'concepto')) !!}
 	</div>
 	<div class="form-group">
 		{!! Form::label('descripcion','Detalle') !!}
 		{!! Form::text('description',$expense->description,['class'=>'form-control','placeholder'=>'detalle del gasto','required']) !!}
 	</div>
 @endsection

 @section('contenido2')
 	<div class="form-group">
 		{!! Form::submit('Editar',['class'=> 'btn btn-primary']) !!}
 	</div>
 	<p class="navbar-text navbar-right">Creado: {{ $expense->created_at}}| Modificado: {{$expense->updated_at}}</p>


 	{!! Form::close() !!}
@endsection
