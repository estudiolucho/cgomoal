{{--dd('est')--}}
@extends('admin/template/main')
@section('title', 'Reportes')
@section('entidad') 
 Reporte de Pagos
@endsection

@section('contenido') 
 	{!! Form::open(['route' => 'reports.payments.list', 'method'=>'POST']) !!}
 	<div class="form-group">
 		{!! Form::label('identificacion','Fecha inicial') !!}
 		{!! Form::date('startdate', \Carbon\Carbon::now()) !!} 
 		{!! Form::label('identificacion','Fecha final') !!}
 		{!! Form::date('enddate', \Carbon\Carbon::now()) !!} 
 	</div>
 	<div class="form-group">
 		{!! Form::submit('Buscar',['class'=> 'btn btn-primary']) !!}
 	</div>

 	
 @endsection
 @section('contenido2')	


 	{!! Form::close() !!}
@endsection