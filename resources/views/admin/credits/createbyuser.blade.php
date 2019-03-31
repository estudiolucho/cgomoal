
@extends('admin/template/main')
@section('title', 'Creditos')
@section('entidad') 
 	Registrar Credito a  {{ $user->name }} {{  $user->lastname}} 
@endsection
@section('contenido')
<div class="container" 	>
	{!! Form::open(['route' => 'credit.store', 'method'=>'POST']) !!}
 	{{--<div class="form-group">
 		{!! Form::label('user_id','Cliente') !!}
 		{!! Form::select('user_id',array('null' =>'Seleccione') + array_pluck($user,'document','id'),['required'=>'required']) !!}
 	</div> --}}


 	<div class="form-group"> 
 		{!! Form::label('usuario','Documento') !!}
 		{!! Form::input('text','document', $user->document,['readonly']) !!} 
 		{!! Form::input('text','user_id', $user->id,['hidden']) !!} 
 	</div>
validar que fecha de credito no sea mayor a la actual
 	<div class="form-group"> 
 		{!! Form::label('fecha_desembloslo','Fecha de prestamo') !!}
 		{!! Form::date('fecha_desembolso', \Carbon\Carbon::now()) !!} 
 		{{-- {!! Form::date('contribution_date') !!} 		--}}
 	</div>
 	
 	<div class="form-group">
 		{!! Form::label('valor','Valor') !!}
 		{!! Form::input('text','valor_desembolso', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Vr del credito sin puntos','size'=>'20')) !!}

 		{!! Form::label('tasa','Tasa Mensual') !!}
 		@if($user->type == 'socio')
 		<?php
			$data = env("TASA_SOCIO", "5");
		?>
 		{!! Form::input('text','tasa_mensual', $data
			,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'tasa de interes','size'=>'1','readonly')) !!}
 		@else
 		<?php
			$data = env("TASA_CLIENTE", "10");
		?> 
 		{!! Form::input('text','tasa_mensual', $data,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'tasa de interes','size'=>'1','readonly')) !!}
 		@endif

 		{!! Form::label('cuotas','Cuotas') !!}
 		{!! Form::input('text','cuotas', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Ingrese Cuotas','size'=>'3')) !!}
 	</div>
 	<div class="form-group ">
 		{!! Form::label('descripcion','Detalle') !!}
 		{!! Form::text('descripcion',null,['placeholder'=>'detalle del prestamo','required','size'=>50]) !!}
 	</div>
 	</div>
 @endsection

 @section('contenido2')
 <div class="container" >
 	<div class="form-group">
 		{!! Form::submit('Siguiente (plan de pagos)',['class'=> 'btn btn-primary']) !!}
 	</div>
 </div>

 	{!! Form::close() !!}
@endsection