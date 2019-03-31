
@extends('admin/template/main')
@section('title', 'Flujo de Caja')
@section('entidad') 
 Flujo de Caja por Fechas
@endsection

@section('contenido') 
 	{!! Form::open(['route' => 'reports.cashflow.list', 'method'=>'POST']) !!}
 	<div class="form-group">
 		{!! Form::label('fecha ini','Fecha inicial') !!}
 		{!! Form::date('startdate', \Carbon\Carbon::now()) !!} 
 		{!! Form::label('fechafin','Fecha final') !!}
 		{!! Form::date('enddate', \Carbon\Carbon::now()->addDay()) !!} 
 	</div>
 	<div class="form-group">
 		{!! Form::submit('Buscar',['class'=> 'btn btn-primary']) !!}
 	</div>

 	
 @endsection
 @section('contenido2')	


 	{!! Form::close() !!}
@endsection