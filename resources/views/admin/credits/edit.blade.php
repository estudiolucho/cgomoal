@extends('admin/template/main')
@section('title', 'Creditos')
@section('entidad') 
 	Editar Credito
@endsection

@section('contenido')
	{!! Form::open(['route' => ['credit.update',$credit], 'method'=>'PUT']) !!}
 	<div class="form-group">
 		{!! Form::label('user_id','Cliente') !!}
 		{!! Form::select('user_id', [$users->find($credit->user_id)->document]) !!}
 		{!! Form::input('text','user_id',$credit->user_id,['readonly','size'=>'1','hidden']) !!}	
 	
 	{{-- </div> 
 	<div class="form-group"> --}}
 		{!! Form::label('fecha_desembloslo','Fecha de prestamo') !!}
 		{!! Form::date('fecha_desembolso',\Carbon\Carbon::parse($credit->fecha_desembolso)) !!} 
 		{{-- {!! Form::date('contribution_date') !!} 		--}}


 	</div>
 	<div class="form-group">
 		{!! Form::label('valor','Valor') !!}
 		{!! Form::input('text','valor_desembolso', $credit->valor_desembolso,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'valor del credito','size'=>'5')) !!}

 		{!! Form::label('tasa','Tasa Mensual') !!}
 		{!! Form::input('text','tasa_mensual', $credit->tasa_mensual,array('readonly','required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'tasa de interes','size'=>'1')) !!}
 		{!! Form::label('cuotas','Cuotas') !!}
 		{!! Form::input('text','cuotas', $credit->cuotas ,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Ingrese Cuotas','size'=>'3')) !!}
 	</div>
 	<div class="form-group ">
 		{!! Form::label('descripcion','Detalle') !!}
 		{!! Form::text('descripcion',$credit->descripcion,['placeholder'=>'detalle del gasto','required','size'=>50]) !!}
 	</div>
 	<div class="form-group ">
 		{!! Form::label('descripcion','Saldo Capital') !!}
 		{!! Form::number('saldo_capital',$credit->saldo_capital,['placeholder'=>'detalle del gasto','required','size'=>50]) !!}
 	</div>
 	<div class="form-group ">
 		{!! Form::label('descripcion','Saldo Interes') !!}
 		{!! Form::number('saldo_interes',$credit->saldo_interes,['placeholder'=>'detalle del gasto','required','size'=>50]) !!}
 	</div>
 	<div class="form-group ">
 		{!! Form::label('descripcion','Estado') !!}
 		{!! Form::text('estado',$credit->estado,['placeholder'=>'estado','required','size'=>50]) !!}
 	</div>
 @endsection

 @section('contenido2')
 	<div class="form-group">
 		{!! Form::submit('Editar Credito',['class'=> 'btn btn-primary']) !!}
 	</div>
 	<p class="navbar-text">Todos los derechos Reservados &copy {{ date('Y') }}</p>
 	<hr>
	<p class="navbar-text navbar-right">Creado: {{ $credit->created_at}}| Modificado: {{$credit->updated_at}}</p>


 	{!! Form::close() !!}
@endsection
