
@extends('admin/template/main')
@section('boton')
<a href="{{ url()->previous() }}" class="no-print btn btn-primary">Volver</a>
@endsection
@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Error de Validacion</h4>
                        <p></p>
                    <hr>
                    @if (session('status'))
                        <div class="alert alert-danger">
                            {{ session('status') }} 
                        </div>
                    @endif
                    <p>{{ $text }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection