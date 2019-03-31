<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Payment;
use App\User;
use App\Credit;
use App\Cash_Flow;
use DateTime;
use Carbon\Carbon;
use Excel;
use Session;


class PaymentController extends Controller
{
    //
    public function index(){

    	return view('admin.payments.index');
    }
    public function show($id){
        $payment= Payment::find($id);
        $credit= Credit::find($payment->credit_id); //trae el credito asociado
        $user= User::find($credit->user_id); //trae el usuario asociado
        //dd($payment,$credit,$user->document,$credit->id);
        return view('admin.payments.show')
            ->with('payment',$payment)
            ->with('credit',$credit)
            ->with('user',$user);
    }
    public function edit($id){
    	dd('editar pago',$id);
        $payment= Payment::find($id);
        $credit= Credit::find($payment->credit_id); //trae el credito asociado
        $user= User::find($credit->user_id); //trae el usuario asociado
        //dd($payment,$credit,$user->document,$credit->id);
        return view('admin.payments.edit')
            ->with('payment',$payment)
            ->with('credit',$credit)
            ->with('user',$user);
    }public function update(){
    //public function update(Request $request,$id){    
        //aqui va el codigo para actualizar el registro
        $text='no se debe actualizar los pagos manualmente';
        return view('admin.template.messages')->with('text',$text);
    }

    public function createbycredit($id){
 		Log::info('Metodo createbycredit');
 		$credit= Credit::find($id); //trae el credito asociado
        $user= User::find($credit->user_id); //trae el usuario asociado
 		//$user = User::all('id','document','active')->where('active','1');
 		$dateapply= \Carbon\Carbon::now();
 		$fechacr = \Carbon\Carbon::createFromFormat('Y-m-d', $credit->fecha_desembolso);
        
        // esta parte se uso para creditos que estan creados a fin de año y se hacen pagos en los anticiapdos a la fecha de corte en el primer mes del siguiente pero solo sirve si es el primer mes 
        
        Log::info('Valida si credito es de año anterior y se hace pago anticipado fechacr-dia:'.$fechacr->day.' fechappli-dia:'.$dateapply->day.'fechacr-mes:'.$fechacr->month.' fechaappli-mes:'.$dateapply->month);
        //
        if ($fechacr->day >= $dateapply->day and $fechacr->month >= $dateapply->month and $dateapply->month==1){
            Log::info(' ======antes de modificar fecha dia del credito '.$fechacr->day.' dia de aplicacion '.$dateapply->day);
            $dateapply=$fechacr;
            Log::info(' ======despues de modificar dia del credito '.$fechacr->day.' dia de aplicacion '.$dateapply->day);
        }
        elseif($fechacr->day > $dateapply->day  ){
            Log::info('cuando el dia  del desembolso sea mayor que el dia del sistema, el pago  es anticipado y se  hace en el mes anterior  $dateapply->day=$fechacr->day;');
            Log::info(' antes de modificar dia del aplicar pago '.$fechacr->day.' dia de aplicacion '.$dateapply->day);
            $dateapply->day=$fechacr->day;
            $dateapply->month=($dateapply->month-1);            
            Log::info('dia del credito '.$fechacr->day.' dia de aplicacion '.$dateapply->day);
        }else{
            $dateapply->day=$fechacr->day;
            Log::info('Pago se aplica en el mes corriente con dia del credito '.$dateapply);
        }


 		Log::info('fecha de aplicacion de pago '.$dateapply);
 		//echo 'diacredito: '.$fechacr->day;
 		//dd($dateapply,$credit->fecha_desembolso,$fechacr);
 		//dd($user->document,$credit->id);
 		return view('admin.payments.createbycredit')
 			->with('credit',$credit)
 			->with('user',$user)
 			->with('date',$dateapply);
        //$user = User::all('id','cedula','active')->where('active','1');
        //dd($user);
        //return view('admin.payments.create')->with('user',$user);
    }
    public function process(PaymentRequest $request){
        Log::info('Metodo process llamado desde vista createbycredit');
        //obtengo los datos del credito
        $numcr=$request->credit_id;
        $credit= Credit::find($numcr);
        $fechacredito= $credit->fecha_desembolso;
        $hoy= \Carbon\Carbon::now();
        //obtengo los datos del pago
        $payment= new Payment($request->all());
        
        //obtengo el ultimo pago realizado 
        $lastpayment=  Payment::orderBy('id', 'DESC')->where('credit_id',$numcr)->first();
        //dd('prueba', $lastpayment,$numcr,$credit);
        
        //si hay al menos 1 pago calcular tiempo con base en este
        if ($lastpayment) {
        	Log::info( 'hay por lo menos un pago, calcular int desde el ultimo pago a la fecha');
        	$datetime1 = new DateTime($hoy);
			$datetime2 = new DateTime($lastpayment->created_at); //fecha de creacion de pago
            
            $datetime2 = new DateTime(Carbon::parse($lastpayment->date_payment)); //fecha de aplicacion de pago
            $datetime2 = new DateTime($lastpayment->date_payment);
            //dd($lastpayment->date_payment,$lastpayment->created_at,Carbon::parse($lastpayment->date_payment),$datetime2,$datetime1);
            //echo 'se calcula con base en fecha de aplicacion de ultimo pago '.$datetime2.'<br>';
            if($lastpayment->saldo_capital <=0 ){
                return view('admin.template.messages')->with('text','Credito Cancelado!. No se puede Hacer Pagos');
            }
			$objects=$this->calcularDistribucionUltimoPago($datetime1,$datetime2,$request,$payment,$lastpayment,$credit); 
            //$objects viene con [payment,credit,intmora,dias]
			$objects['payment']->user_create=Auth::user()->username;
            

            $objects['payment']->save();
            $objects['credit']->save();
            Log::info('se creo Pago'.$objects['payment'].' con dias de mora '.$objects['dias']);
            
            Log::info('Preparo el registro para la tabla cash_flow');
            $last_cash_flow= Cash_Flow::orderBy('id', 'DESC')->first();
            $user_create=Auth::user()->username;
            $cash_flow= new Cash_Flow();
            $cash_flow->date=\Carbon\Carbon::now();
            $cash_flow->amount=$objects['payment']->intmora+$objects['payment']->abono_interes+$objects['payment']->abono_capital;
            $cash_flow->amount=$objects['payment']->amount;
            Log::info('valor '.$cash_flow->amount);
            $cash_flow->concept='abono';
            $cash_flow->type='entrada';
            $cash_flow->description="Pago#".$payment->id."| C.C.".$objects['payment']->document." | Credito#".$objects['payment']->credit_id." | ".$objects['payment']->descripcion;
            $cash_flow->balance=$last_cash_flow->balance+$objects['payment']->amount;
            $cash_flow->user_create=Auth::user()->username;
            
            $cash_flow->save();
            Log::info('se creo Cash_Flow '.$cash_flow);
			
            //dd($objects['credit'],$objects['payment'],$objects['dias'],$objects['intmora']);
			return Redirect::route('payment.created')->with('objects',$objects); //evita que al recargar vuelva a insertar el registro
            //return view('admin.payments.summary')->with('objects',$objects); // codigo  original
        }else{ 
        	Log::info('es el primer pago al credito,calcular int con la fecha del prestamo ');
        	$datetime1 = new DateTime($hoy);
			$datetime2 = new DateTime($fechacredito);
			//dd($datetime1,$datetime2);
			//$fechaaplicarpago= new DateTime();			
			//cambiar el dia al que si hizo el prestamo
        	//$fechaaplicarpago->setDate($datetime2->format('%m'),3,1);
        	//dd($fechaaplicarpago);

			//esto se debe pasar a funcion calcularDistribucion()
			$objects=$this->calcularDistribucion($datetime1,$datetime2,$request,$payment,$credit);
            $objects['payment']->user_create=Auth::user()->username;


            $objects['payment']->save();
            $objects['credit']->save();
            Log::info('se creo Pago'.$objects['payment'].' con dias de mora '.$objects['dias']);

            Log::info('preparo el registro para la tabla cash_flow');
            $last_cash_flow= Cash_Flow::orderBy('id', 'DESC')->first();
            $cash_flow= new Cash_Flow();
            $cash_flow->date=\Carbon\Carbon::now();
            $cash_flow->amount=$objects['payment']->amount;
            Log::info('valor '.$cash_flow->amount);
            $cash_flow->concept='abono';
            $cash_flow->type='entrada';
            $cash_flow->description="Pago#".$payment->id."| C.C.".$objects['payment']->document." | Credito#".$objects['payment']->credit_id." | ".$objects['payment']->descripcion;
            $cash_flow->balance=$last_cash_flow->balance+$objects['payment']->amount;
            $cash_flow->user_create=Auth::user()->username;
            
            $cash_flow->save();
            Log::info('se creo Cash_Flow '.$cash_flow);

            return Redirect::route('payment.created')->with('objects',$objects);
			//return view('admin.payments.summary')->with('objects',$objects);
        }
    }
    // con  esta funcion se evita que al recargar F5  la pagina se inserte de nuevo el registro
    public function created(){
        $objects=Session::get('objects');
        if($objects){
        return view('admin.payments.summary')->with('objects',$objects);
        }else{
            return view('admin.template.messages')->with('text','Proceso terminado No se puede Recargar la Pagina');
        }

    }

    private function calcularDistribucionUltimoPago(DateTime $datetime2,DateTime $datetime1,$request,$payment,$lastpayment,$credit){
        Log::info('Metodo calcularDistribucionUltimoPago, cuando hay pagos previos');
    	$objects=[];
        $devolucion=0;
    	$desembolso=$credit->valor_desembolso;
        $ptemora=$lastpayment->saldointmora;
        $pteinteres=$lastpayment->saldo_interes;
        $ptecapital=$lastpayment->saldo_capital;
        $objects=$this->generaobjetos($credit,$request,$datetime2,$datetime1,$ptemora,$pteinteres,$ptecapital,$payment);
        return ($objects);
    }
    
    private function calcularDistribucion(DateTime $datetime2,DateTime $datetime1,$request,$payment,$credit){
        Log::info('Metodo calcularDistribucion, cuando es el primer pago');
        $objects=[];
        $devolucion=0;
        $desembolso=$credit->valor_desembolso;
        $ptemora=0;
        $pteinteres=$credit->saldo_interes;
        $ptecapital=$credit->saldo_capital;
        $objects=$this->generaobjetos($credit,$request,$datetime2,$datetime1,$ptemora,$pteinteres,$ptecapital,$payment);
        return ($objects);
/*      borrar el 20 de enero si no se ha necesitado
        Log::info('Metodo calcularDistribucion');
        $objects=[];
        $devolucion=0;
        //#####echo 'estoy en la funcion calcularDistribucion ';
        $desembolso=$credit->valor_desembolso;
        $pteinteres=$credit->saldo_interes;
        $ptecapital=$credit->saldo_capital;
        $ptemora=0;

        $tasa=$credit->tasa_mensual;
        $user=User::find($credit->user_id);
        if($user->type == 'socio'){  
        $tasamora = env("TASA_MORA_SOCIO", "1.1");
        }else{$tasamora = env("TASA_MORA_CLIENTE", "1.1");}
        Log::info('tasa de mora adicional ('.$tasamora.')');
        $pago=$request->amount;
        $fechapago=$request->date_payment;

        $intervalo = $datetime2->diff($datetime1); //meses y dias transcurridos
        $meses= $intervalo->m;
        //CALCULAR DISTRIBUCION DEL PAGO (abono  interes y capital)
        //si meses=0 el abono es anticipado se paga todo a capital  fechaaplicar  pago es elmismo dia del credito
        if ($meses == 0) {
            Log::info('ya habia hecho pago en el mes o  pagó antes del corte-');
            $intmora=0;
            $dias=0;
            //al pago se le descuenta lo que tenga pendiente en mora
            $subpago=$pago-$ptemora;
            if($subpago >= 0){
                Log::info('el pago cubre la mora actual y la pendiente, mora al dia-');
                $payment->intmora=$ptemora;
                $payment->saldointmora=0;
                echo 'no se cobra interes- '.'<br>';
                //cuando no tenga intereses ptes, el abono anticipado se carga a capital
                if($pteinteres == 0 ){
                    Log::info('no tiene intereses pendientes se abona a capital- ');
                    $payment->abono_interes=0;
                    $payment->abono_capital=$subpago;
                    $payment->saldo_interes=0;
                    $payment->saldo_capital=$ptecapital-$subpago;
                    $credit->saldo_interes=0;
                    $credit->saldo_capital=$ptecapital-$subpago;
                    if($payment->saldo_capital <= 0){
                        Log::info('el saldo capital es menor o igual a cero: '.$payment->saldo_capital.' credito cancelado -');
                        $devolucion=abs($payment->saldo_capital);
                        $credit->saldo_capital=0;
                        $payment->saldo_capital=0;
                        $payment->abono_capital=$ptecapital;
                        $payment->amount=$payment->abono_capital+$payment->abono_interes+$payment->intmora;
                        $credit->estado=0;
                            Log::info('devolver el exedente'.$devolucion.'- ');}
                }else{
                    $disp=$subpago-$pteinteres;
                    if($disp >= 0){
                        Log::info('lo que pago es mas de los intereses pendientes- ');
                        $payment->abono_interes=$pteinteres;
                        $payment->abono_capital=$disp;
                        $payment->saldo_interes=0;
                        $payment->saldo_capital=$ptecapital-$disp;
                        $credit->saldo_interes=0;
                        $credit->saldo_capital=$ptecapital-$disp;
                        if($payment->saldo_capital <= 0){
                            Log::info('el saldo capital es menor o igual a cero: '.$payment->saldo_capital.' credito cancelado -');
                            $devolucion=abs($payment->saldo_capital);
                            $credit->saldo_capital=0;
                            $payment->saldo_capital=0;
                            $payment->abono_capital=$ptecapital;
                            $payment->amount=$payment->abono_capital+$payment->abono_interes+$payment->intmora;
                            $credit->estado=0;
                            Log::info('devolver el exedente'.$devolucion.'- ');
                        }
                    }else{
                        Log::info('lo que pago no cubre los interese pendientes- ');
                        $payment->abono_interes=$subpago;
                        $payment->abono_capital=0; //no se hace abono a capital
                        $payment->saldo_interes=$pteinteres-$subpago;
                        $payment->saldo_capital=$ptecapital;// no cambia
                        $credit->saldo_interes=$pteinteres-$subpago;
                        $credit->saldo_capital=$ptecapital;// no cambia
                    }
                }
            }else{
                Log::info('el pago es menor a la mora actual y la pendiente ');
                $payment->intmora=$pago;
                $payment->saldointmora=abs($subpago);
                echo 'no se cambia lo demas-';
                $payment->abono_interes=0;
                $payment->abono_capital=0;
                $payment->saldo_interes=$pteinteres;
                $payment->saldo_capital=$ptecapital;
                $credit->saldo_capital=$ptecapital;
                $credit->saldo_interes=$pteinteres;
            }
        }else{
            Log::info('lleva por lo menos un mes desde el desembolso. ');
            $int1=$this->calIntMes($meses,$tasa,$ptecapital); //intereses de los meses corrientes
            $aplicamora=true; //variable global
            $dias= $intervalo->d;
                //si meses desde el ultimo pago es mayor a 1  dias=dias+((meses-1*30))
                if ($meses>1) {
                    Log::info('meses en moradesde el Desembolso -'.($meses-1));
                    $dias=$dias+(($meses-1)*30);
                }else{
                    Log::info('meses desde el Desembolso 1 o menos');

                } 
            //calcular la mora 
            $intmora=$this->calculaMora($intervalo,$ptecapital,$tasamora);

            //al pago se le descuenta la mora y lo que tenga pendiente en mora
            $subpago=$pago-($intmora+$ptemora);
            if($subpago >= 0){
                Log::info('el pago cubre la mora actual y la pendiente, mora al dia-');
                $payment->intmora=$intmora+$ptemora;
                $payment->saldointmora=0;
            
                //al pago se le descuenta los intereses actuales y los acumulados
                //si lo que queda es mayor que 0 se abona a capital  
                //sino el valorabsoluto delo que quedo se inserta en saldo interes
                $disp=$subpago-($int1+$pteinteres); 
                if($disp >= 0 ){
                    Log::info('el pago fue mayor al  interes pendiente+interes corriente-  ');
                    $payment->abono_interes=$int1+$pteinteres;
                    echo $disp;
                    $payment->abono_capital=$disp;
                    $payment->saldo_interes=0;
                    $payment->saldo_capital=$ptecapital-$disp;
                    $credit->saldo_interes=0;
                    $credit->saldo_capital=$ptecapital-$disp;

                    if($payment->saldo_capital <= 0){
                        Log::info('el saldo capital es menor o igual a cero: '.$credit->saldo_capital.' credito cancelado-');
                        $devolucion=abs($payment->saldo_capital);
                        $credit->saldo_capital=0;
                        $payment->saldo_capital=0;
                        $payment->abono_capital=$ptecapital;
                        $payment->amount=$payment->abono_capital+$payment->abono_interes+$payment->intmora;
                        $credit->estado=0;
                        Log::info('devolver el exedente '.$devolucion);

                    }
                }else{ 
                    Log::info('el pago fue menor al interes pendiente. ');
                    $payment->abono_interes=$subpago;
                    $payment->abono_capital=0;
                    $payment->saldo_interes=abs($disp);
                    $payment->saldo_capital=$ptecapital;
                    $credit->saldo_capital=$ptecapital;
                    $credit->saldo_interes=abs($disp);
                }
            }else{
                Log::info('el pago es menor a la mora actual y la pendiente-');
                $payment->intmora=$pago;
                $payment->saldointmora=abs($subpago);
                Log::info('no se cambia abono interes, abono capital, -');
                $payment->abono_interes=0;
                $payment->abono_capital=0;
                $payment->saldo_interes=$pteinteres+$int1;
                $credit->saldo_interes=$pteinteres+$int1;
                $payment->saldo_capital=$ptecapital;                
                $credit->saldo_capital=$ptecapital;
            }
            //crear el resumen de  registro de pago  o ir al final del primer if
            //crear el resumen de  saldos del credito
        }
        //CALCULAR INTERES DE MORA  (puede ser un campo aplicamora en la tabla credit)
        //dd('resumen del pago a aplicar ',$credit,$payment,'interes de mora: '.$intmora);
        $objects=[
        'credit'    => $credit,
        'payment'   =>$payment,
        'intmora'   =>$intmora,
        'dias'      =>$dias,
        'devolucion'=>$devolucion
        ];
        return ($objects);
*/
    }
    private function generaobjetos($credit,$request,DateTime $datetime2,DateTime $datetime1,$ptemora,$pteinteres,$ptecapital,$payment){
        Log::info('Metodo generaobjetos en el pago ');
        $objects=[];
        $devolucion=0;
        $tasa=$credit->tasa_mensual;
        $user=User::find($credit->user_id);
        if($user->type == 'socio'){  
            //$tasamora = env("TASA_MORA_SOCIO", "1");
            $tasamora = $this->tasaMoraSocio();
        }else{//$tasamora = env("TASA_MORA_CLIENTE", "2");
            $tasamora = $this->tasaMoraCliente();
        }
        Log::info('tasa de mora ('.$tasamora.')');
        $pago=$request->amount;
        $fechapago=$request->date_payment;

        $intervalo = $datetime2->diff($datetime1); //meses y dias transcurridos
        Log::info('intervalo años:'.$intervalo->y.' meses:'.$intervalo->m.' dias:'.$intervalo->d.' total dias'.$intervalo->format('%R%a'));
        $meses=$intervalo->m+($intervalo->y*12);
        //voyaqui
        //CALCULAR DISTRIBUCION DEL PAGO (abono  interes y capital)
        //si meses=0 el abono es anticipado se paga todo a capital  fechaaplicar  pago es elmismo dia del credito
        if ($meses == 0) {
            Log::info('ya habia hecho pago en el mes o  pago es antes del corte, no aplica mora');
            $intmora=null;
            $dias=null;
            //al pago se le descuenta lo que tenga pendiente en mora
            $subpago=$pago-$ptemora;
            if($subpago >= 0){
                Log::info('el pago cubre la mora actual y la pendiente, mora al dia-');
                $payment->intmora=$ptemora;
                $payment->saldointmora=0;
                Log::info('no se cobra interes- ');
                //cuando no tenga intereses ptes, el abono anticipado se carga a capital
                if($pteinteres == 0 ){
                    Log::info('no tiene intereses pendientes, queda '.$subpago.' para abonar capital');
                    $payment->abono_interes=0;
                    $payment->abono_capital=$subpago;
                    $payment->saldo_interes=0;
                    $payment->saldo_capital=$ptecapital-$subpago;
                    $credit->saldo_interes=0;
                    $credit->saldo_capital=$ptecapital-$subpago;
                    if($payment->saldo_capital <= 0){
                        Log::info('el saldo capital es menor o igual a cero: '.$payment->saldo_capital.' credito cancelado -');
                        $devolucion=abs($payment->saldo_capital);
                        $credit->saldo_capital=0;
                        $payment->saldo_capital=0;
                        $payment->abono_capital=$ptecapital;
                        $payment->amount=$payment->abono_capital+$payment->abono_interes+$payment->intmora;
                        $credit->estado=0;
                        Log::info('devolver el exedente'.$devolucion.'- ');
                    }
                    Log::info('Saldo Capital'.$payment->saldo_capital);
                }else{
                    Log::info('tiene intereses pendientes- ');
                    $disp=$subpago-$pteinteres;
                    if($disp >= 0){
                        Log::info('el pago es mayor a  los intereses pendientes- ');
                        $payment->abono_interes=$pteinteres;
                        $payment->abono_capital=$disp;
                        $payment->saldo_interes=0;
                        $payment->saldo_capital=$ptecapital-$disp;
                        $credit->saldo_interes=0;
                        $credit->saldo_capital=$ptecapital-$disp;
                        if($payment->saldo_capital <= 0){
                        Log::info('el saldo capital es menor o igual a cero: '.$payment->saldo_capital.' credito cancelado -');
                        $devolucion=abs($payment->saldo_capital);
                        $credit->saldo_capital=0;
                        $payment->saldo_capital=0;
                        $payment->abono_capital=$ptecapital;
                        $payment->amount=$payment->abono_capital+$payment->abono_interes+$payment->intmora;
                        $credit->estado=0;
                        Log::info('devolver el exedente'.$devolucion.'- ');
                        }
                    }else{
                        Log::info('el pago no alcanza a cubrir los interese pendientes- ');
                        $payment->abono_interes=$subpago;
                        $payment->abono_capital=0; //no se hace abono a capital
                        $payment->saldo_interes=$pteinteres-$subpago;
                        $payment->saldo_capital=$ptecapital;// no cambia
                        $credit->saldo_interes=$pteinteres-$subpago;
                        $credit->saldo_capital=$ptecapital;// no cambia
                    }
                }
            }else{
                Log::info('el pago es menor a la mora actual y la pendiente ');
                $payment->intmora=$pago;
                $payment->saldointmora=abs($subpago);
                //echo 'no se cambia lo demas- '.'<br>';
                $payment->abono_interes=0;
                $payment->abono_capital=0;
                $payment->saldo_interes=$pteinteres;
                $payment->saldo_capital=$ptecapital;
                $credit->saldo_capital=$ptecapital;
                $credit->saldo_interes=$pteinteres;
            }
        }else{
            Log::info('lleva por lo menos un mes desde el ultimo pago. ');
            $int1=$this->calIntMes($meses,$tasa,$ptecapital); //intereses de los meses corrientes
            $aplicamora=true; //variable global 
            //calcular la mora 
            $dias= $intervalo->d;
                //si meses desde el ultimo pago es mayor a 1  dias=dias+((meses-1*30))
            if ($meses>1) {
                Log::info('meses en moradesde el ultimo pago -'.($meses-1));
                $dias=$dias+(($meses-1)*30);
            }else{
                Log::info('la mora en el pago no supera 30 dias');
            }
            //calcular la mora 
            $intmora=$this->calculaMora($intervalo,$ptecapital,$tasamora);//esta linea hace lo comentado que sigue

            /*if($aplicamora){
                //#####echo 'el credito tiene mora ';
                $dias= $intervalo->d;
                //si meses desde el ultimo pago es mayor a 1  dias=dias+((meses-1*30))
                if ($meses>1) {
                    Log::info('meses en moradesde el ultimo pago -'.($meses-1));
                    $dias=$dias+(($meses-1)*30);
                }else{
                    Log::info('meses desde el ultimo pago 1 o menos');

                }
                //probar bien esta validacion de dias cuando este en el mismo mes pero atrasado
                $diasgracia=env("DIAS_GRACIA", "6");
                if($dias >= $diasgracia){
                    Log::info('la mora excede los dias de gracia-');
                    $intmora=$this->calIntMoraDias($dias,$tasamora,$ptecapital);
                    //$intmora=$this->calIntMoraMeses(($meses-1),$tasamora,$ptecapital);
                    Log::info('mora es: '.$intmora.' -');
                }else{
                    Log::info('esta en los dias de gracia');
                    $intmora=0;
                }
                //dd('fechapago: '.$fechapago,'fechacredito: '.$fechacredito,'dias: '.$intervalo->format('%a'), 'interes de  '.$meses.' y '.$dias.': '.$int1.' '.$intmora );
            }*/
            
            //al pago se le descuenta la mora y lo que tenga pendiente en mora
            $subpago=$pago-($intmora+$ptemora);
            if($subpago >= 0){
                Log::info('el pago cubre la mora actual y la pendiente, mora al dia- ');
                $payment->intmora=$intmora+$ptemora;
                $payment->saldointmora=0;
            
                //despues de descontar mora del pago, se descuenta los intereses actuales y los acumulados si lo que queda es mayor que 0 se abona a capital  
                //sino el valor absoluto delo que quedo se inserta en saldo interes
                
                $disp=$subpago-($int1+$pteinteres); 
                if($disp >= 0 ){
                    Log::info('el pago fue mayor al  interes pendiente+interes corriente, interes al dia-  ');
                    $payment->abono_interes=$int1+$pteinteres;
                    $payment->abono_capital=$disp;
                    $payment->saldo_interes=0;
                    $payment->saldo_capital=$ptecapital-$disp;
                    $credit->saldo_interes=0;
                    $credit->saldo_capital=$ptecapital-$disp;
                    
                    if($payment->saldo_capital <= 0){
                        Log::info('el saldo capital es menor o igual a cero: '.$payment->saldo_capital.' credito cancelado -');
                        $devolucion=abs($payment->saldo_capital);
                        $credit->saldo_capital=0;
                        $payment->saldo_capital=0;
                        $payment->abono_capital=$ptecapital;
                        $payment->amount=$payment->abono_capital+$payment->abono_interes+$payment->intmora;
                        $credit->estado=0;
                        Log::info('devolver el exedente'.$devolucion.'- ');
                    }
                    //dd('pago a aplicar: '.$payment,'saldos a aplicar '.$credit->saldo_capital );

                }else{ 
                    Log::info('el pago fue menor al interes pendiente. ');
                    $payment->abono_interes=$subpago;
                    $payment->abono_capital=0;
                    $payment->saldo_interes=abs($disp);
                    $payment->saldo_capital=$ptecapital;
                    $credit->saldo_capital=$ptecapital;
                    $credit->saldo_interes=abs($disp);
                }
            }else{
                Log::info('el pago es menor a la mora actual y la pendiente');
                $payment->intmora=$pago;
                $payment->saldointmora=abs($subpago);
                echo 'no se cambia lo demas-'.'<br>';
                $payment->abono_interes=0;
                $payment->abono_capital=0;
                $payment->saldo_interes=$pteinteres+$int1;
                $payment->saldo_capital=$ptecapital;
                $credit->saldo_capital=$ptecapital;
                $credit->saldo_interes=$pteinteres+$int1;

            }
            //crear el resumen de  registro de pago  o ir al final del primer if
            //crear el resumen de  saldos del credito
        }
        //dd('resumen del pago a aplicar ',$credit,$payment,'interes de mora: '.$intmora);
        $objects=[
        'credit'    => $credit, //credito a  actualizar 
        'payment'   =>$payment, //pago a crear 
        'intmora'   =>$intmora, //interes de mora
        'dias'      =>$dias,        //dias de mora en pagar
        'devolucion'=>$devolucion
        ];
        return ($objects);
        //return ([$credit,$payment,$intmora]);
        //return view('admin.payments.summary');
        //$this->summary($credit,$payment,$intmora);//no sirvio
    }

    private function calIntMes($mes,$tasa,$salcapital){
        $int=$mes*($tasa/100)*$salcapital;
        return round($int,2);
    }
    private function calIntMoraDias($dia,$tasamora,$salcapital){
        //se convierte los dias de mora a meses con fracciones
        $mes=($dia*100/30)/100;
        $int=$mes*($tasamora/100)*$salcapital;
        return round($int,2);
    }
    //para calculo de mora por todo el mes no se usa en su lugar se hace por dias
    private function calIntMoraMeses($meses,$tasamora,$salcapital){
        //dd($mes);
        echo 'calculando  mora '.$meses.'*'.($tasamora/100).'*'.$salcapital.'<br>';
        $int=$meses*($tasamora/100)*$salcapital;
        return round($int,2);
    }
    public function summary(Credit $credit,Payment $payment,$intmora){
    	return view('admin.payments.summary')->with('credit', $credit)->with('payment', $payment)->with('mora',$intmora);
    }


    //buscar los creditos asociados a una cedula
    public function findcreditorig(Request $request){
        $iduser= $request->input('document');
        //$idcredit= $request->input('credit_id');
        if ($iduser){
            $user = User::where('document','=', $iduser)->first();
            if($user){
              $creditos = Credit::all()->Where('user_id',$user->id)->where('estado',1); //se trata como una coleccion de objetos
                return view('admin.payments.listcredits')
                    ->with('credits', $creditos)
                    ->with('users',$user);
            }else{
                echo 'el numero de cedula ingresado no existe'.'<br>';
            }
        }else{
            echo 'no ingreso ningun numero'.'<br>';
        }
    }
    //buscar los creditos asociados por la cedula ingresada
    public function findcredit(Request $request){
        $doc= $request->input('document');
        if ($doc){
            return $this->posfindcredit($doc);
            //this->posfindcredit(Crypt::encrypt($doc));
        }else{
            $text='Informacion invalida, Verifique';
            return view('admin.template.messages')->with('text',$text);
        }   	
    }public function posfindcredit($doc){
        //$doc=Crypt::decrypt($doc);
        Log::info('payment-posfindcredit---------------------');  
        $user = User::where('document','=', $doc)->first();
        if($user){
          $creditos = Credit::all()->Where('user_id',$user->id)->where('estado',1); //se trata como una coleccion de objetos
          $int=$this->generaInt($creditos,$user);
          $mora=$this->generaMora($creditos,$user);
          
            return view('admin.payments.listcredits')
                ->with('credits', $creditos)
                ->with('users',$user)
                ->with('int',$int)
                ->with('mora',$mora);
        }else{
            $text='el numero de cedula ingresado no existe';
            return view('admin.template.messages')->with('text',$text);
        }

    }
    //listar los pagos realizados a un credito
    public function list($id){
        //$id=Crypt::decrypt($id);
    	$payments= Payment::all()->where('credit_id',$id);
        $credit= Credit::find($id);
        $user = User::find($credit->user_id);
    	//dd($payments,$user);
    	return view('admin.payments.list')->with('payments',$payments)->with('id',$id)->with('user',$user);
    }
    //muestra tabla de intereses generados por cada credito del usuario
    public function generaInt( $credits,User $user){
        Log::info('metodo generaInt');
        //$credits= Credit::all()->where('estado', 1);
        //$users = User::all('id','document','name','lastname');
        $hoy= \Carbon\Carbon::now();
        $intereses=array();
        $intporcobrar=0;
        //$intereses;
        foreach ($credits as $credit) {
            Log::info('Credito'.$credit->id.'----------------------');
            $fechacr = \Carbon\Carbon::createFromFormat('Y-m-d', $credit->fecha_desembolso);
            $lastpayment=  Payment::orderBy('id', 'DESC')->where('credit_id',$credit->id)->first();
            Log::info('Ultimo Pago '.$lastpayment);
            //traer el ultimo pago realizado al credito
            //si hay al menos 1 pago calcular tiempo con base en este
            if ($lastpayment) {
                //si credito tiene pagos se saca el intervalo con la aplicacion del  ultimo pago
                $fultimo = new DateTime($lastpayment->date_payment); //podria ser con la fecha de creacion del pago
                Log::info('payment generaInt ultimo Pago '.$lastpayment->date_payment);
                $intervalo = $hoy->diff($fultimo); //meses y dias transcurridos 
                $tasa=$credit->tasa_mensual;
                $ptecapital=$lastpayment->saldo_capital;
                $meses=$intervalo->m+($intervalo->y*12);
                Log::info('payment meses interes desde utimo pago '.$meses);
                $int1=$this->calIntMes($meses,$tasa,$ptecapital); //intereses de los meses 
                Log::info('interes anterior'.$lastpayment->saldo_interes.' Interes actual '.$int1);
                $intereses[$credit->id]=$int1+$lastpayment->saldo_interes;

            }else{
                //no hay pagos al credito, se saca con la fecha del credito
                $intervalo = $hoy->diff($fechacr); //meses y dias transcurridos 
                $tasa=$credit->tasa_mensual;
                $ptecapital=$credit->saldo_capital;
                $meses=$intervalo->m+($intervalo->y*12);
                Log::info('payment meses interes desde desembolso '.$meses);
                $int1=$this->calIntMes($meses,$tasa,$ptecapital); //intereses de los meses 
                $intereses[$credit->id]=$int1;
            }
            //$intereses[$credit->id]=$int1+$credit->saldo_interes;
            $intporcobrar=$intporcobrar+$intereses[$credit->id]; //acumula los int de los creditos
            //echo 'fechacr '.$fechacr.' interes '.$int1.' '.$intereses[$credit->id];
        }
        //dd('voy a calcular intereses',$credits,$intereses[1],$intporcobrar);
        return ($intereses);
        /*return view  ('reports.intporcobrar')
            ->with('credits',$credits)
            ->with('intereses',$intereses)
            ->with('porcobrar',$intporcobrar)
            ->with('users',$user)
            ->with('mora',$mora);
            */
    }
    public function generaMora( $credits,User $user){
        Log::info('metodo generaMora');
        $hoy= \Carbon\Carbon::now();
        $mora=array();
        $intporcobrar=0;
        foreach ($credits as $credit) {
            Log::info('Credito'.$credit->id.'----------------------');
            $user=User::find($credit->user_id);
            if($user->type == 'socio'){  
                $tasamora = $this->tasaMoraSocio();
            }else{//$tasamora = env("TASA_MORA_CLIENTE", "1.1");
                $tasamora = $this->tasaMoraCliente();
            }
            $fechacr = \Carbon\Carbon::createFromFormat('Y-m-d', $credit->fecha_desembolso);
            $lastpayment=  Payment::orderBy('id', 'DESC')->where('credit_id',$credit->id)->first();//traer el ultimo pago realizado al credito
            //si hay al menos 1 pago calcular tiempo con base en este
            if ($lastpayment) {
                //si credito tiene pagos se saca el intervalo con la aplicacion del  ultimo pago
                Log::info('credito '.$lastpayment->credit_id.' ultimo pago creado el '.$lastpayment->created_at.' aplicado el '.$lastpayment->date_payment);
                $fultimo = new DateTime($lastpayment->date_payment); //podria ser con la fecha de creacion del pago
                $intervalo = $hoy->diff($fultimo); //meses y dias transcurridos 
                Log::info('Ultimo Pago'.$fultimo->format('Y-m-d H:i:s').'|'.$hoy->format('Y-m-d H:i:s').'|'.$intervalo->m);
                if ($intervalo->m >=1 ||$intervalo->y >0){
                    $ptecapital=$lastpayment->saldo_capital;
                    $intmora=$this->calculaMora($intervalo,$ptecapital,$tasamora);
                    Log::info('intervalo años:'.$intervalo->y.' meses:'.$intervalo->m.' mora pendiente desde el ultimo pago '.$lastpayment->saldointmora.', mora actual '.$intmora);
                    $intmora=$intmora+$lastpayment->saldointmora;
                }else{
                    $intmora=$lastpayment->saldointmora;
                    Log::info('intervalo dias:'.$intervalo->d.' mora pendiente desde el ultimo pago '.$lastpayment->saldointmora);
                }
            }else{
                //sino se saca con la fecha del credito
                Log::info('no hay pagos anteriores');
                $intervalo = $hoy->diff($fechacr); //meses y dias transcurridos 
                if ($intervalo->m >=1||$intervalo->y >0){
                    Log::info('intervalo años:'.$intervalo->y.' intervalo meses:'.$intervalo->m);
                    $ptecapital=$credit->saldo_capital;
                    $intmora=$this->calculaMora($intervalo,$ptecapital,$tasamora);
                }else{$intmora=0;}
            }
            $mora[$credit->id]=$intmora;

        }
        return ($mora);
    }
    public function tasaMoraSocio(){
        return (env("TASA_MORA_SOCIO", "1"));
    }public function tasaMoraCliente(){
        return (env("TASA_MORA_CLIENTE", "2"));
    }

    public function calculaMora($intervalo,$ptecapital,$tasamora){
        $dias= $intervalo->d;
        $meses= $intervalo->m;
        $anos=$intervalo->y;
        Log::info('calculaMora recibe -años'.$intervalo->y.'meses '.$intervalo->m.' dias '.$intervalo->d.' total dias '.$intervalo->format('%a').' capital '.$ptecapital.' tasamora '.$tasamora);
        //si meses desde el ultimo pago es mayor a 1  dias=dias+((meses-1*30))
        if ($meses>1||$anos>0) {
            Log::info('años en mora -'.($anos).'meses en mora -'.($meses-1));
            $dias=$dias+(($meses-1)*30)+$anos*365;
        }else{
            Log::info('meses desde el ultimo pago 1 o menos');
        }
        //probar bien esta validacion de dias cuando este en el mismo mes pero atrasado
        $diasgracia=env('DIAS_GRACIA', "10");
        if($dias >= $diasgracia){
            Log::info('dias de mora '.$dias.', excede los dias de gracia '.$diasgracia);
            $intmora=$this->calIntMoraDias($dias,$tasamora,$ptecapital);
            //$intmora=$this->calIntMoraMeses(($meses-1),$tasamora,$ptecapital);
            Log::info('mora es: '.$intmora.' -');
        }else{
            Log::info('esta en los dias de gracia');
            $intmora=0;
        }
        return $intmora;
        //dd('fechapago: '.$fechapago,'fechacredito: '.$fechacredito,'dias: '.$intervalo->format('%a'), 'interes de  '.$meses.' y '.$dias.': '.$int1.' '.$intmora );
    }



    //buscar pagos efectuados por rango de fecha
    public function search(){
        return view('reports.paymentsearch');
    }public function listByDate(Request $request){
        $date1=$request->startdate;
        $date2=$request->enddate;
        Session::put('date1', $date1);
        Session::put('date2', $date2);
        $q = Input::get ( 'q' );
        //if($q != ""){
            //$user = User::where ( 'created_at', 'LIKE', '%' . $q . '%' )->orWhere ( 'created_at', 'LIKE', '%' . $q . '%' )->paginate (5)->setPath ( '' );
        $payments = Payment::whereBetween('created_at', array($date1, $date2))->paginate (5)->setPath ( '' );//->first();
        $payments = Payment::whereBetween('created_at', array($date1, $date2))->get();//->first();
        $users = User::all('document','name','lastname');
        //$pagination = $payments->appends ( array ('q' => Input::get ( 'q' ) ) );
        //}
        return view('reports.paymentsbydate')->with('payments',$payments)->with('id','Desde: '.$date1.' Hasta: '.$date2)->with('users',$users);
        dd('lista de pagos',$request,$payments);
    }
    public function downloadExcel($type)
    {
        //dd( Session::get('date1'),Session::get('date2'));
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and contributions tables, retrieving
        // the contributions table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.

        $payments = Payment::join('users', 'users.document', '=', 'payments.document')->join('credits', 'credits.id','=', 'payments.credit_id')
        ->select(
          'payments.id',  
          'users.document', 
          'payments.date_payment',
          'payments.created_at',
          'payments.amount',
          'payments.intmora',
          'payments.saldointmora',
          'payments.abono_interes',
          'payments.abono_capital',
          'payments.saldo_interes',
          'payments.saldo_capital',
          'payments.descripcion')
        ->whereBetween('payments.created_at', array(Session::get('date1'),Session::get('date2')))
        //->groupBy('payments.credit_id')->groupBy('contributions.concept_id')
        ->get();
        // Initialize the array which will be passed into the Excel
        // generator.
        $paymentsArray = []; 
        // Define the Excel spreadsheet headers
        $paymentsArray[] = ['#','Cedula', 'Fecha Aplicacion', 'Fecha Realizacion', 'Valor Pagado', 'Interes Mora', 'Saldo Mora', 'Abono Interes', 'Abono Capital','Saldo Interes', 'Saldo Capital','Descripcion'];
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($payments as $payment) {
        $paymentsArray[] = $payment->toArray();
        }
        $this->generaExcel($paymentsArray,'Pagos');
    }

    //recibe un array de los registros a exportar en excel
    private function generaExcel($datos,$tabla){
        //esto es igual en todos sepuede poner en una funcion
        // Generate and return the spreadsheet 
        Excel::create( $tabla.Session::get('date1').'a'.Session::get('date2'), function($excel) use ($datos) {
            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Pagos');
            $excel->setCreator('Gomoa')->setCompany('FondoGomoa');
            $excel->setDescription('Pagos a Creditos');
            // Build the spreadsheet, passing in the payments array
            $excel->sheet('Pagos', function($sheet) use ($datos) {
                $sheet->fromArray($datos, null, 'A1', false, false);
            });
        })->download('xlsx');
    }

    //buscar pagos efectuados a un credito
    public function searchbycredit(){
        return view('reports.paymentssearchbycredit');
    }public function inputCredit(Request $request){
        $id=$request->credit_id;
        //$id=Crypt::encrypt($id);
        return $this->list($id);
    }


    

}
