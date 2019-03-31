@extends('admin/template/main')
@section('title', 'Pagos')
@section('entidad') 
 	<h2>{!! Form::label('lab','Realizar Pago a: ')!!}</h2>
 	<h2>{!! Form::label('lab',''.$user->name ." ". $user->lastname )!!}</h2>
@endsection
@section('boton')
	<p align="right">
 	<a href="{{ route ('payment.index') }}" class="btn btn-primary">Ir a Pagos</a>
 	</p>
@endsection
@section('contenido')
<div class="container" >
        {!! Form::open(['route' => 'payment.process', 'method'=>'POST']) !!}
        <div class="form-group">
                {!! Form::label('credito','#CREDITO') !!}
                {!! Form::input('text','credit_id', $credit->id,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'credito','size'=>'5','readonly')) !!}

                {!! Form::label('usuario','USUARIO') !!}
                {!! Form::input('text','document', $user->document,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'credito','size'=>'10','readonly')) !!}
                {{--!! Form::input('text','document', $user->document,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'credito','size'=>'10','hidden')) !!--}}
        </div>
        <div class="form-group">
                {{-- puede que pidan poder cambiar la fecha de creacion
                {!! Form::label('fecha de pago','Fecha de pago') !!}
                {!! Form::date('date_payment', \Carbon\Carbon::now()) !!}
                --}}
                {!! Form::date('date_payment', $date,array('hidden')) !!}
                
        </div>
        <div class="input-group">
                {!! Form::label('valor','Valor a pagar :') !!}
                <span class="input-group-addon" >$</span>
                <h1 style=font-size:80px >{!! Form::number('amount', null,array('required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'valor a pagar','size'=>'9','min'=>'0','step'=>'0.1')) !!}</h1>
        </div>
        <div class="form-group ">
                {!! Form::label('descrip','Descripcion') !!}
                {!! Form::text('descripcion',null,['placeholder'=>'detalle del pago','required','size'=>150]) !!}
        </div>
 </div>
 @endsection

 @section('contenido2')
 <div class="container" >
 	<ul class="table-info"><a> {!! Form::label('credito','¡Atencion!: Informar que al valor pagado se descuenta los intereses de mora antes de abonar a los intereses y capital, el pago se aplica a la ultima fecha de corte') !!}</a> </ul>
 	<ul><li><a class="table-danger"> {!! Form::label('credito','Tasa de Mora Clientes: '.env("TASA_MORA_CLIENTE")  ) !!} </a></li></ul>
 	<ul><li><a class="table-danger"> {!! Form::label('credito','Tasa de Mora Socios: '.env("TASA_MORA_SOCIO")  ) !!} </a></li></ul>
 	<ul><a class="table-danger"> {!! Form::label('credito','pte incluir en la vista el valor del pago total ') !!} </a></ul>
 	<div class="form-group">
 		{!! Form::submit('Crear',['class'=> 'btn btn-primary']) !!}
 	</div>

 	{!! Form::close() !!}
 </div>
@endsection