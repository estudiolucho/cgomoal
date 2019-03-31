
@extends('admin/template/main')
@section('title', 'Creditos')
@section('entidad') 
 	Registrar Credito 
 	este no se debe usar mas
@endsection
@section('contenido')
	{!! Form::open(['route' => 'credit.store', 'method'=>'POST']) !!}
 	{{--
 	<div class="form-group">
 		{!! Form::label('user_id','Cliente') !!}
 		{!! Form::select('user_id',array('' =>'Seleccione') + array_pluck($users,'document','id'),['required'=>'required']) !!}
 	</div>
 	--}}
 	<div class="form-group">
 		{!! Form::label('user_id','Cliente') !!}
 		{!! Form::input('text','document',null,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Documento','size'=>'20')) !!}
 	</div>  
 	<div class="form-group"> 
 	   validar que fecha de credito no sea mayor a la actual
 		{!! Form::label('fecha_desembloslo','Fecha de prestamo') !!}
 		{!! Form::date('fecha_desembolso', \Carbon\Carbon::now()) !!} 
 		{{-- {!! Form::date('contribution_date') !!} 		--}}
 	</div>
 	<div class="form-group">
 		{!! Form::label('valor','Valor') !!}
 		{!! Form::input('text','valor_desembolso', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Vr del credito sin puntos','size'=>'20')) !!}

 		{!! Form::label('tasa','Tasa Mensual') !!}
 		{!! Form::input('text','tasa_mensual', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'tasa de interes','size'=>'1')) !!}
 		linea 31  validar tipo usuario para aplicar tasa_mensual
 		@if(true)	

 		@endif

 		{!! Form::label('cuotas','Cuotas') !!}
 		{!! Form::input('text','cuotas', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Ingrese Cuotas','size'=>'3')) !!}
 	</div>
 	<div class="form-group ">
 		{!! Form::label('descripcion','Detalle') !!}
 		{!! Form::text('descripcion', null ,['placeholder'=>'detalle del prestamo','required','size'=>50]) !!}
 	</div>
 @endsection
 @section('contenido2')
 	<div class="form-group">
 		{!! Form::submit('Crear',['class'=> 'btn btn-primary']) !!}
 	</div>
 	{!! Form::close() !!}
@endsection