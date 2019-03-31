<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
  
  <div class="navbar-brand">   
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
     <span class="navbar-toggler-icon"></span>
     <span class="sr-only">Toggle navigation</span>
    </button>
    <div class="navbar-header">
      {{--<a class="navbar-brand" href="#">Fondo Gomoa</a>--}}
      <a href="{{ URL::to('/home') }}">
      <img src="{{ URL::asset('img/logo.png') }}" class="admin-logo-nav" style="border-radius:5px" alt="profile Pic" height="25" width="25"></a>
    </div>
  </div>
  {{-- <a class="navbar-brand" href="#">MENUss</a>

  <div class="collapse navbar-collapse" id="navbarSupportedContent"> original--}}
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >
    @if(Auth::user())
      <ul class="nav navbar-nav mr-auto">
        @if(Auth::user()->isOperator() or Auth::user()->isAdmin())
          <li class="nav-item active">
            <a class="btn btn-secondary text-primary font-weight-bold" href="{{ route('user.index')}}" style="width:120px">Usuarios <span class="sr-only">(current)</span></a>
          </li>

        
          <li class="nav-item active">
            <a class="btn btn-secondary text-primary font-weight-bold" href="{{ route('credit.index')}}" style="width:120px">Creditos <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item active">
            <a class="btn btn-secondary text-primary font-weight-bold" href="{{ route('payment.index') }}" style="width:120px">Pagos <span class="sr-only">(current)</span></a>
          </li>

          <li class="nav-item active">
            <li class="dropdown">
              <a class="btn btn-secondary dropdown-toggle text-primary font-weight-bold" data-toggle="dropdown" id="dropdownMenu" style="width:120px">Gastos<span class="caret"></span><span class="sr-only sr-only-focusable">(current)</span></a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('expense.index') }}">Listado Gastos</a></li>  
                <li><a class="dropdown-item" href="{{ route('econcept.index') }}">Listado Conceptos</a></li> 
              </ul>
            </li>
          </li>
          <li class="nav-tabs active">
            <li class="dropdown">
              <a class="btn btn-secondary dropdown-toggle text-primary font-weight-bold" data-toggle="dropdown" style="width:120px">Aportes <span class="sr-only">(current)</span></a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('contribution.index') }}">Listado Aportes</a></li>  
                <li><a class="dropdown-item" href="{{ route('cconcept.index') }}">Listado Conceptos</a></li>
                <div class="dropdown-divider"></div>
                <li><a class="dropdown-item text-danger font-weight-bold"  href="{{ route('contribution.retirement') }}">Retiro de Aportes</a></li>
              </ul>
            </li>
          </li> 
        @endif
          
        @if(Auth::user()->isUser())
          <li class="nav-tabs active">
            <a class="nav-link" href="{{ route('payment.posfindcredit', ['document' => Auth::user()->document ] ) }}">Mis Creditos activos <span class="sr-only">(current)</span></a>
          </li>
        @endif
        @if(Auth::user()->isSocio() and Auth::user()->isUser())
          <li class="nav-tabs active">
            <a class="nav-link" href="{{ route('contribution.find', ['document' => Crypt::encrypt(Auth::user()->document) ] ) }}">Mis Aportes <span class="sr-only">(current)</span></a>
          </li>
        @endif

        <li class="nav-item active">
          <a class="btn btn-secondary text-primary font-weight-bold" href="{{ route('credit.simulator') }}" style="width:120px">Simulador<span class="sr-only">(current)</span></a>
        </li>
          
        @if(Auth::user()->isOperator() or Auth::user()->isAdmin())
          <li>
            <li class="dropdown">
              <a class="btn btn-secondary dropdown-toggle text-primary font-weight-bold" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expand="false" style="width:120px">Reportes <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expand="false" >Creditos <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{ route('credit.generaInt')}}">Intereses por Aplicar</a></li>
                  <li><a class="dropdown-item" href="{{ route('credit.canceled')}}">Creditos Cancelados</a></li>
                  </ul>
                </li>
                <div class="dropdown-divider"></div>
                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expand="false" >Pagos <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li ><a class="dropdown-item" href="{{ route('reports.payments.search')}}">Pagos por Lapso</a></li>
                    <li><a class="dropdown-item" href="{{ route('reports.payments.searchbycredit')}}">Pagos por Credito</a></li>
                  </ul>
                </li>
                <div class="dropdown-divider"></div>
                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expand="false" >Aportes <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('reports.contributions.search')}}">Aportes por Lapso</a></li>
                <li><a class="dropdown-item" href="{{ route('reports.contributions.search2')}}">Aportes por Lapso (agrupado)</a></li>
                <li><a class="dropdown-item" href="{{ route('reports.contributions.search3')}}">Aportes por Lapso-Socio</a></li>
                <li><a class="dropdown-item" href="{{ route('reports.contributions.search4')}}">Aportes por Socio</a></li>
                  </ul>
                </li>
                
                <div class="dropdown-divider"></div>
                <li>
                  <a class="dropdown-item" href="{{ route('reports.expenses.search')}}">Gastos por Lapso</a>
                </li>
                <div class="dropdown-divider"></div>
                <li><a class="dropdown-item" href="{{ route('cashflow.index')}}">Flujo de Caja</a></li>
                <div class="dropdown-divider"></div>
                <li><a class="dropdown-item" href="{{ route('user.list')}}">Listado Usuarios</a></li>
                <div class="dropdown-divider"></div>
  
                @if(Auth::user()->isAdmin())
                  <li class="dropdown-submenu">
                    <a class="dropdown-item dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expand="false" >Ajustes <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="{{ route('cashflow.create')}}">Nuevo Flujo de Caja</a></li>
                      <li><a class="dropdown-item" href="{{ route('cashflow.select')}}">Editar Flujo de Caja </a></li>
                      <li class="dropdown-submenu">
                                <a href="#" class="dropdown-item dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-submenu">
                                        <a href="#" class="dropdown-item dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Action</a></li>
                                            <li><a href="#">Another action</a></li>
                                            <li><a href="#">Something else here</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#">One more separated link</a></li>
                                        </ul>
                                    </li>
                                </ul>
                      </li>
                    </ul>
                  </li>
                @endif

              </ul>
            </li>
          </li>
        @endif

          {{--
            <li class="nav-item active">
              <a class="nav-link" href="#">Vin</a>
            </li>

            
            <li class="nav-item">
              <a class="nav-link disabled" href="#">Disabled</a>
            </li>
          --}}

          <ul class="navbar-nav navbar-toggler-right">
            <li  class="nav-item active">
              <li><a class="nav-link ">{{date('d/m/Y')}} </a></li>
            </li>
            <li>
              <a  class="btn btn-primary text-white font-weight-bold dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expand="false">{{ Auth::user()->name }} {{ Auth::user()->lastname}} <span class="caret"></span></a>
              <ul class="dropdown-menu">
                {{-- <li><a href="{{ route('logout')}}">Cerrar Sesion</a></li>--}}
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        Cerrar Sesion
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                </li>
                  <a class="dropdown-item" href="{{ route('cashflow.delete') }}">Eliminar Credito</a>
                <li>
                  <a Borrar
                </li>
              </ul>
            </li> 
          </ul>
        
          {{--  comentarios asi  
          <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Buscar">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Busqueda</button>
          </form>
          --}}
      </ul>
    @endif
  </div>
</nav>