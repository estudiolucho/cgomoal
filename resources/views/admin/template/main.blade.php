<!DOCTYPE html>
<html lang="en">
	<link rel="stylesheet" href="{{ asset ('plugins/bootstrap/css/bootstrap.min.css') }}">
	
	<head>
		<meta charset="UTF-8">
		<title>@yield('title','home')| GOMOA</title>
		

		<script type="text/javascript">
			function divPrint() {
    		// Some logic determines which div should be printed...
		    // This example uses div3.
    		$("#div3").addClass("printable");
    		window.print();}
    		$('.dropdown-toggle').dropdownHover(options);
		</script>
		
		

		{{-- ESTO NO HACE NADA SON PRUEBAS

		<link rel="stylesheet" href="{{ asset ('plugins/bootstrap/css/bootstrap.min.css') }}">
		<script  src="{{ asset('plugins/jquery/jquery-3.2.1.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('plugins/jquery/jQuery.print.js') }}"></script>
		<script  src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>



		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="http://www.position-absolute.com/creation/print/jquery.printPage.js"></script>

		<script type="text/javascript" src="{{ asset('plugins/jquery/jquery-printPage.js') }}"></script>
		<script type="text/javascript" src="{{ asset('plugins/jquery/jquery-print.js') }}"></script>
		
		<script  src="{{ asset('plugins/jquery/jquery-printPage.js') }}"></script>
		<script  src="{{ asset('plugins/jquery/jquery-print.js') }}"></script>
		<script>    
            $(function() {
                $("#ele2").find('.print-link').on('click', function() {
                    //Print ele2 with default options
                    $.print("#ele2");
                });

                $("#ele4").find('button').on('click', function() {
                    //Print ele4 with custom options
                    $("#ele4").print({
                        //Use Global styles
                        globalStyles : false,

                        //Add link with attrbute media=print
                        mediaPrint : false,

                        //Custom stylesheet
                        stylesheet : "http://fonts.googleapis.com/css?family=Inconsolata",

                        //Print in a hidden iframe
                        iframe : false,

                        //Don't print this
                        noPrintSelector : ".avoid-this",

                        //Add this on top
                        append : "Free jQuery Plugins!!!<br/>",

                        //Add this at bottom
                        prepend : "<br/>jQueryScript.net!"
                    });
                });
                $("#ele3").find('button').on('click', function() {
                    //Print ele4 with custom options
                    $("#ele3").print({
                        //Use Global styles
                        globalStyles : false,

                        //Add link with attrbute media=print
                        mediaPrint : false,

                        //Custom stylesheet
                        stylesheet : "http://fonts.googleapis.com/css?family=Inconsolata",

                        //Print in a hidden iframe
                        iframe : false,

                        //Don't print this
                        noPrintSelector : ".avoid-this",

                        //Add this on top
                        append : "Free jQuery Plugins!!!<br/>",

                        //Add this at bottom
                        prepend : "<br/>jQueryScript.net!"
                    });
                });    
            });
        </script>
		--}}




		<style type="text/css">
			table{
				border-collapse: collapse;
				width: 70%;
				margin:0 auto;
			}
		</style>
	</head>
	<body class="admin-body" >
		{{--
		<script type="text/javascript">
    	$(window).load(function(){  //your code here
		});
		</script>
		<div id="printme">
				Rohit Kumar - My Public NoteBook
		</div>
		<button class="print"> Print this a </button>
		<button class="print-link no-print" onclick="jQuery('#printme').print()"> Print this </button>
		<button class="print-link no-print" onclick="jQuery('#ele4').print()"> Print this </button>
		--}}
		@if(Auth::user()->isAdmin())
		Main:como calcular el saldo de los socios al pasar el tiempo..
		los creditos quedan con decimales en saldo  ej0.3
		-revisar lo que estaba trabajando en cashflow selec y cashflow delete
		-ver contributioncontroler funcion balanceByUser que esta sin terminar
		@endif		
		

		@if(Auth::user()->isAdmin() or Auth::user()->isOperator()) 
          
        
		<div align="right">
			<small >
				Tasa Socio: {{env("TASA_SOCIO", "1")}} 
				Tasa Mora Socio: {{env("TASA_MORA_SOCIO", "1")}} || 
				Tasa Cliente: {{env("TASA_CLIENTE", "1")}}
				Tasa Mora Cliente: {{env("TASA_MORA_CLIENTE", "2")}}
			</small>
		</div>
		@endif
		@include('admin/template/partials/nav')
		

		<div id="ele4">
			<table class="table  table-sm bg-faded " >
				<tr>
					<td style="width:45%"><h2> @yield('entidad') </h2></td>
					<td ><img src="{{ URL::asset('img/logo.png') }}" class="admin-logo-nav " alt="profile Pic" height="80" width="80" style="border-radius:25px" ></td>
					<td style="width:25%">@yield('herramientas')</td>
					<td style="width:30%" align="right">@yield('boton')</td>
					{{--<td style="width:15%"><p align="right"></p></td>--}}
				</tr>
			</table>

				<div id="ele1" >
					<section class="section-admin" >
						<div class="panel panel-default" >

							<div  class="panel-heading" >
								<hr>
								<h3 class="panel-title" align="center">@yield('title2')</h3>
							</div>
							<div class="panel-body" >

								{{--  falta agregar los mensajes de confirmacion
								@include('flash::message')
								--}}
								@include('admin.template.partials.errors')
								@yield('contenido')
							</div>
						</div>
					</section>
				</div>
			
		</div>

		<section class="section-admin">
			<hr>
			@yield('contenido2')
		</section>
		
		<script type="text/javascript" src="{{ asset('plugins/jquery/jquery-3.2.1.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('plugins/jquery/jQuery.print.js') }}"></script>
		<script  src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>
	</body>
	<footer >
		<nav class="navbar navbar-collapse bg-faded" >
			<div class="container-fluid">
				<div class="panel-title">
					<p class="navbar-text">Fondo Gomoa Todos los derechos Reservados &copy {{ date('Y') }}</p>	
				</div>
			</div>
		</nav>
	</footer>

		


</html>
