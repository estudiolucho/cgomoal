
@extends('admin/template/main')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="alert alert-info" align="center" role="alert">
                    <h4 class="alert-heading">PANEL DE ACCESO</h4>
                        <p>Utilice los botones de barra superior para acceder a los servicios</p>
                    <hr>
                    @if (session('status'))
                        <div class="alert alert-info">
                            {{ session('status') }} ss
                        </div>
                    @endif

                    <a class="font-weight-bold" > Bienvenido! </a>
                    @if (session('status'))
                        <div class="alert alert-info">
                            {{ session('status') }}
                        </div>
                    @endif
                    <a  aria-haspopup="true" aria-expand="false">{{ Auth::user()->name }} {{ Auth::user()->lastname}} <span class="caret"></spam></a>

                </div>
                
                {{--
                <div class="panel-heading bg-faded" align="center">
                <a class="font-weight-bold">Usuario</a>
                </div>
                
                <div class="panel-body " align="center">
                    @if (session('status'))
                        <div class="alert alert-info">
                            {{ session('status') }}
                        </div>
                    @endif
                    <a  aria-haspopup="true" aria-expand="false">{{ Auth::user()->name }} {{ Auth::user()->lastname}} <span class="caret"></spam></a>
                    <p allign="center"> <img src="{{ URL::asset('img/logo.png') }}" class="admin-logo-nav" alt="profile Pic" height="250" width="250" > </p>
                </div>
                --}}
                {{--<a allign="center"> <img src="{{ URL::asset('img/logo.png') }}" class="admin-logo-nav" alt="profile Pic" height="250" width="250" > </a>--}}
            </div>
        </div>
    </div>
</div>
{{--
<a href="{{URL::to('admin/credit/generaInt')}}" class="btnPrint">test print</a>   
    <script type="text/javascript">
        $(document).ready(function(){
            $(".btnPrint").printPage();
        });
    </script>
    --}}
@endsection
