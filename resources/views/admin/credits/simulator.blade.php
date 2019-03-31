
@extends('admin/template/main')

<head>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://www.position-absolute.com/creation/print/jquery.printPage.js"></script>
</head>
@section('contenido')
	
	<script type="text/javascript">
		$(document).ready(function(){
			$(".btnPrint").printPage();
		});
	</script>
<div class="container" >
	{!! Form::open(['route' => 'credit.amortizationdemo', 'method'=>'POST']) !!}

 	{{--
 	<div class="form-group">
 		{!! Form::label('user_id','Cliente') !!}
 		{!! Form::select('user_id',array('' =>'Seleccione') + array_pluck($users,'document','id'),['required'=>'required']) !!}
 	</div>
 	<div class="form-group"> 
 		{!! Form::label('fecha_desembloslo','Fecha de prestamo') !!}
 		{!! Form::date('fecha_desembolso', \Carbon\Carbon::now()) !!} 
 		 {!! Form::date('contribution_date') !!} 		
 	</div>
 	--}}
 
 	<div class="form-group">
 		{!! Form::label('valor','Valor') !!}
 		{!! Form::input('text','valor_desembolso', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Vr del credito sin puntos','size'=>'20')) !!}

 		{!! Form::label('tasa','Tasa Mensual') !!}
 		{!! Form::input('text','tasa_mensual', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'tasa de interes','size'=>'1')) !!}
 		{!! Form::label('cuotas','Cuotas:') !!}
 		{!! Form::input('text','cuotas', null ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Ingrese Cuotas','size'=>'3')) !!}
 	</div>
 </div>
 @endsection

 @section('contenido2')
 <div class="container" >
 	<div class="form-group">
 		{!! Form::submit('Mostrar',['class'=> 'btn btn-primary']) !!}
 	</div>
</div>
 	{!! Form::close() !!}
@endsection