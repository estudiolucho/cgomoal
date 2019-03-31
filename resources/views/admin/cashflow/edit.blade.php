@extends('admin/template/main')
@section('title', 'Gastos')
@section('entidad') 
 	Editar Registro de Flujo de Caja
@endsection
@section('boton')
	{{--<p align="right">
 	<a href="{{ route ('expense.index') }}" class="btn btn-primary">Ir a Gastos</a>
 	</p>--}}
 @endsection
@section('contenido')

	{!! Form::open(['route' => ['cashflow.update',$cashflow], 'method'=>'PUT']) !!}
 	<div class="form-group">
 		{!! Form::label('concept_id','Concepto') !!}
 		{!! Form::select('concept', array(null =>'Seleccione Concepto') + array('ajuste_ent' =>'Ajuste de Entrada','ajuste_sal'=>'Ajuste de Salida','abono'=>'Abono a Credito','credito'=>'Credito','gasto'=>'Gasto','aporte'=>'Aporte'),$cashflow->concept,array('required' => 'required') ) !!}	
 	</div>
 	<div class="form-group">
 		{!! Form::label('concept_id','Tipo') !!}
 		{!! Form::select('type', array(null =>'Seleccione Tipo','entrada' =>'Entrada','salida'=>'Salida'),$cashflow->type,array('required' => 'required') ) !!}	
 	</div>
 	<div class="form-group">
 		{!! Form::label('description','Fecha') !!}
 		{!! Form::date('date', \Carbon\Carbon::parse($cashflow->date)) !!} 		
 	</div>
 	<div class="form-group">
 		{!! Form::label('description','Fecha Creacion') !!}
 		{!! Form::date('date', \Carbon\Carbon::parse($cashflow->created_at)) !!} 		
 	</div>
 	<div class="form-group">
 		{!! Form::label('cantidad','Valor') !!}
 		{!! Form::number('amount', $cashflow->amount,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Valor','step'=>'0.1')) !!}
 	</div>
 	<div class="form-group">
 		{!! Form::label('cantidad','Saldo') !!}
 		{!! Form::number('balance', $cashflow->balance,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Saldo','step'=>'0.1')) !!}
 	</div>
 	<div class="form-group">
 		{!! Form::label('descripcion','Detalle') !!}
 		{!! Form::text('description',$cashflow->description,['class'=>'form-control','placeholder'=>'detalle del gasto','required']) !!}
 	</div>
 @endsection

 @section('contenido2')
 	<div class="form-group">
 		{!! Form::submit('Actualizar',['class'=> 'btn btn-primary']) !!}
 	</div>

 	{!! Form::close() !!}
@endsection
