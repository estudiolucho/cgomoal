<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use App\Http\Requests;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreditRequest;
use App\Credit;
use App\Cash_Flow;
use App\User;
use DateTime;
use App\Payment;

class CreditController extends Controller
{
    public function index(){
    	$credits = Credit::where('estado',1)->orderBy('id','DESC')->paginate(15);
    	//$credits = Credit::all();
        $users = User::all('id','document','name','lastname');
        //dd($users);
        return view('admin.credits.index')
            ->with('credits',$credits)
            ->with('users',$users);
    }
    public function show(){

    }
    

    public function edit($id){
        $credit= Credit::find($id);
        $user = User::all('id','document');
        return view('admin.credits.edit')
            ->with('credit',$credit)
            ->with('users',$user);
    }
    /**
     *Update the specified resource in storage
     *
     *@param \Illuminate\Http\Request $request
     *@param int $id
     *@return \Illuminate\Http\Response
     */
    public function update(CreditRequest $request,$id){
        $credit = Credit::find($id);
        $credit->fill($request->all());
        $now = new DateTime();
        $credit->updated_at = $now;
        //dd($credit);
        $credit->user_update=Auth::user()->username;
        $credit->save();
        return redirect()->route('credit.index');
    }


    public function create(){
        return view('admin.credits.new');
    }public function findUser(Request $request){
        $iduser= $request->input('document');
        //$idcredit= $request->input('credit_id');
        if ($iduser){
            $user = User::where('document','=', $iduser)->first();
            if($user){
                if($user->active == 1){
                    return view('admin.credits.createbyuser')->with('user',$user);
                }else{
                    $text='El usuario: '.$user->name." ".$user->lastname.' esta inactivo, Comuniquese con el Administrador';
                    return view('admin.template.messages')->with('text',$text);
                }  
            }else{
                $text='El número de cédula ingresado no existe';
                return view('admin.template.messages')->with('text',$text);
            }
        }else{
            $text='Por favor use un número de Cédula';
            return view('admin.template.messages')->with('text',$text);
        }
    }


    public function createbyuser($id){
        $user = User::find($id);
        return view('admin.credits.createbyuser')->with('user',$user);
    }
    //funcion llamada por formulario en vista createbyuser
    public function store(CreditRequest $request){
        $iduser= $request->input('document');
        if ($iduser){
            Log::info('CreditContr: Metodo Store');
            Log::info('se busca la cedula ingresada: '.$iduser);
            $user = User::where('document','=', $iduser)->first();
            if($user){
                Log::info('usuario existe'.$iduser);
                if($user->active == 1 ){
                    Log::info('usuario activo puede crear el  credito  ');
                    //dd($request);
                    /*
                    $credit= new Credit($request->all());
                    $credit->user_id=$user->id;
                    $credit->estado=true;
                    $credit->saldo_interes=0;
                    $credit->saldo_capital=$credit->valor_desembolso;
                    */
                    //return $this->amortization($request,$credit);
                    return $this->amortization($request);
                }else{
                    Log::info( 'usuario exite pero no esta activo ');
                }
            }else{Log::info('el numero de cedula ingresado no existe  ');
            }
        }else{Log::info('no ingreso ningun numero  ');
        }
    }
    public function amortization(Request $request){
        //echo ' estoy aqui '. $credit;
        $iduser= $request->input('document');
        $user = User::where('document','=', $iduser)->first();
        
        $crdate=$request->fecha_desembolso;
        $cramount=$request->valor_desembolso;
        $crintpercent=$request->tasa_mensual;
        $crintrate=$crintpercent/100;
        $crmonths=$request->cuotas;

        // pago=[(r)(vrprestamo)]/[1-(1+r)^-n], donde r es la tasa expresada decimalmente y n es el tiempo de financiación en meses, entonces si reemplazamos los valores dados en el problema en esta fórmula, obtenemos que: pago=[(0,01)(10.000)]/[1-(1+0,01)^-60] adicionar campo fecha_aplica_pago :fecha del mes actual donde se aplica el pago (mismo dia del credito)
        $paymentamount=($crintrate*$cramount)/(1-pow((1+$crintrate),(-1*$crmonths)));
        $a = array(); // array of columns
        $per =array();
        $salini=array();
        $pint=array();
        $pcap=array();
        $pcuota=array();
        $salfin=array();
        for($p=0;$p<=$crmonths;$p++){
            if( $p == 0 ){
                $per[$p]=$p;
                $salini[$p]= 0 ;
                $pint[$p]= 0 ;
                $pcap[$p]= 0 ;
                $pcuota[$p]= 0 ;
                $salfin[$p]= $cramount ;
            }else{
            $per[$p]=$p;
            $salini[$p]=round($salfin[$p-1]);
            $pint[$p]=round($salini[$p]*$crintrate);
            $pcuota[$p]=round($paymentamount);
            $pcap[$p]=round($pcuota[$p]-$pint[$p]);
            $salfin[$p]=round($salini[$p]-$pcap[$p]);
            }

        }
        return view('admin.credits.amortization',['cramount'=>$cramount, 'crdate'=>$crdate ,'crmonths'=>$crmonths,'crintpercent'=>$crintpercent,'paymentamount'=>$paymentamount,'per'=>$per,'salini'=>$salini,'pint'=>$pint,'pcap'=>$pcap,'pcuota'=>$pcuota,'salfin'=>$salfin,'user'=>$user]);
        //dd($cramount,$crmonths,$crintrate,$paymentamount,$per,$salini,$pint,$pcap,$pcuota,$salfin);
            
    }public function confirm( CreditRequest $request ){
    //    $credit= new Credit($request->all());
        $credit= new Credit($request->all());
        $credit->estado=true;
        $credit->saldo_interes=0;
        $credit->saldo_capital=$credit->valor_desembolso;
        $credit->user_create=Auth::user()->username;
        $credit->save();
        Log::info('Credito Creado: '.$credit);
        Log::info('inserto el registro para la tabla cash_flow');
        $last_cash_flow= Cash_Flow::orderBy('id', 'DESC')->first();
        $cash_flow= new Cash_Flow();
        $cash_flow->date= \Carbon\Carbon::now();
        $cash_flow->amount=$credit->valor_desembolso;
        $cash_flow->concept='credito';
        $cash_flow->type='salida';
        //dd (User::find($credit->user_id)->document);
        $cash_flow->description="Credito#".$credit->id." | C.C. ".User::find($credit->user_id)->document." Cuotas ".$credit->cuotas." Tasa ".$credit->tasa_mensual." | ".$credit->descripcion;
        $cash_flow->balance=$last_cash_flow->balance+(-1*$credit->valor_desembolso);
        $cash_flow->user_create=Auth::user()->username;
        $cash_flow->save();
        Log::info('Cash_Flow Creado: '.$cash_flow);
        //dd($cash_flow);
        //return $this->index();
        return Redirect::route('credit.created');
    }
    public function created(){
        return $this->index();
    }

    public function simulator(){
            return view('admin.credits.simulator');
    }
    public function amortizationdemo(Request $request){
        //$iduser= $request->input('document');
        //$user = User::where('document','=', $iduser)->first();
        
        $crdate=$request->fecha_desembolso;
        $cramount=$request->valor_desembolso;
        $crintpercent=$request->tasa_mensual;
        $crintrate=$crintpercent/100;
        $crmonths=$request->cuotas;

        // pago=[(r)(vrprestamo)]/[1-(1+r)^-n], donde r es la tasa expresada decimalmente y n es el tiempo de financiación en meses, entonces si reemplazamos los valores dados en el problema en esta fórmula, obtenemos que: pago=[(0,01)(10.000)]/[1-(1+0,01)^-60] adicionar campo fecha_aplica_pago :fecha del mes actual donde se aplica el pago (mismo dia del credito)
        $paymentamount=($crintrate*$cramount)/(1-pow((1+$crintrate),(-1*$crmonths)));
        $a = array(); // array of columns
        $per =array();
        $salini=array();
        $pint=array();
        $pcap=array();
        $pcuota=array();
        $salfin=array();
        for($p=0;$p<=$crmonths;$p++){
            if( $p == 0 ){
                $per[$p]=$p;
                $salini[$p]= 0 ;
                $pint[$p]= 0 ;
                $pcap[$p]= 0 ;
                $pcuota[$p]= 0 ;
                $salfin[$p]= $cramount ;
            }else{
            $per[$p]=$p;
            $salini[$p]=round($salfin[$p-1]);
            $pint[$p]=round($salini[$p]*$crintrate);
            $pcuota[$p]=round($paymentamount);
            $pcap[$p]=round($pcuota[$p]-$pint[$p]);
            $salfin[$p]=round($salini[$p]-$pcap[$p]);
            }

        }

        //dd($crmonths,$paymentamount,$salfin,$credit);
        //echo 'antes de pasar a la vista amortizacion  '.$credit;
        return view('admin.credits.amortizationdemo',['cramount'=>$cramount, 'crdate'=>$crdate ,'crmonths'=>$crmonths,'crintpercent'=>$crintpercent,'paymentamount'=>$paymentamount,'per'=>$per,'salini'=>$salini,'pint'=>$pint,'pcap'=>$pcap,'pcuota'=>$pcuota,'salfin'=>$salfin]);
        //dd($cramount,$crmonths,$crintrate,$paymentamount,$per,$salini,$pint,$pcap,$pcuota,$salfin);
            
    }

    public function generaInt(){
        $credits= Credit::all()->where('estado', 1);
        $users = User::all('id','document','name','lastname');
        $hoy= \Carbon\Carbon::now();
        $intereses=array();
        $intporcobrar=0;$moraporcobrar=0;
        //$intereses;
        foreach ($credits as $credit) {
            Log::info('CreditContr #'.$credit->id.'----------------------');
            //////////////// para int mora
            $user =User::find($credit->user_id);
            if($user->type == 'socio'){  
                $tasamora = $this->tasaMoraSocio();
            }else{//$tasamora = env("TASA_MORA_CLIENTE", "1.1");
                $tasamora = $this->tasaMoraCliente();
            }
            ///////////////
            $fechacr = \Carbon\Carbon::createFromFormat('Y-m-d', $credit->fecha_desembolso);
            $ultimopago=  Payment::orderBy('id', 'DESC')->where('credit_id',$credit->id)->first();//traer el ultimo pago realizado al credito
            //si hay al menos 1 pago calcular tiempo con base en este
            if ($ultimopago) {
                //si credito tiene pagos se saca el intervalo con el ultimo pago
                $fultimo = new DateTime($ultimopago->date_payment);
                $intervalo = $hoy->diff($fultimo); //meses y dias transcurridos 
                $tasa=$credit->tasa_mensual;
                $ptecapital=$credit->saldo_capital;
                $meses=$intervalo->m+($intervalo->y*12);
                Log::info('reporte-intpor cobrar meses interes desde pago '.$meses);
                $int1=$this->calIntMes($meses,$tasa,$ptecapital); //intereses de los meses 
                
                //////////calcula mora del credito
                if ($intervalo->m >=1 ||$intervalo->y >0){
                    $ptecapital=$ultimopago->saldo_capital;
                    $intmora=$this->calculaMora($intervalo,$ptecapital,$tasamora);
                    Log::info('intervalo años:'.$intervalo->y.' intervalo meses:'.$intervalo->m.' mora pendiente desde el ultimo pago '.$ultimopago->saldointmora.', mora actual '.$intmora);
                    $intmora=$intmora+$ultimopago->saldointmora;
                }else{
                    $intmora=$ultimopago->saldointmora;
                    Log::info('mora pendiente desde el ultimo pago '.$ultimopago->saldointmora);
                }
                $ultmimospagos[$credit->id]=$ultimopago->date_payment;
            }else{
                //sino, se saca con la fecha del credito
                $intervalo = $hoy->diff($fechacr); //meses y dias transcurridos 
                $tasa=$credit->tasa_mensual;
                $ptecapital=$credit->saldo_capital;
                $meses=$intervalo->m+($intervalo->y*12);
                Log::info('reporte- intpor cobrar meses interes desde desembolso '.$meses);
                $int1=$this->calIntMes($meses,$tasa,$ptecapital); //intereses de los meses 
                //////////calcula mora del credito
                Log::info('no hay pagos anteriores');
                $intervalo = $hoy->diff($fechacr); //meses y dias transcurridos 
                if ($intervalo->m >=1||$intervalo->y >0){
                    Log::info('intervalo años:'.$intervalo->y.' intervalo meses:'.$intervalo->m);
                    $ptecapital=$credit->saldo_capital;
                    $intmora=$this->calculaMora($intervalo,$ptecapital,$tasamora);
                }else{$intmora=0;}
                $ultmimospagos[$credit->id]="...";
                ///////////7
            }
            $intereses[$credit->id]=$int1+$credit->saldo_interes;
            $intporcobrar=$intporcobrar+$intereses[$credit->id];
            //echo 'fechacr '.$fechacr.' interes '.$int1.' '.$intereses[$credit->id];
            ///////// array con la mora de cada credito
            $mora[$credit->id]=$intmora;
            $moraporcobrar=$moraporcobrar+$intmora;
            ///////////7
            
        }
        //dd('voy a calcular intereses',$credits,$intereses[1],$intporcobrar);
        return view  ('reports.intporcobrar')
            ->with('credits',$credits)
            ->with('intereses',$intereses)
            ->with('porcobrar',$intporcobrar)
            ->with('mora',$mora)
            ->with('mporcobrar',$moraporcobrar)
            ->with('ultpagos',$ultmimospagos)
            ->with('users',$users);
    }
    ///////////////// metodos adicionales 
    public function tasaMoraSocio(){
        return (env("TASA_MORA_SOCIO", "1.3"));
    }public function tasaMoraCliente(){
        return (env("TASA_MORA_CLIENTE", "2.3"));
    }
    private function calIntMes($mes,$tasa,$salcapital){
        $int=$mes*($tasa/100)*$salcapital;
        return($int);
    }
    public function calculaMora($intervalo,$ptecapital,$tasamora){
        $dias= $intervalo->d;
        $meses= $intervalo->m;
        $anos=$intervalo->y;
        Log::info('CreditContr--calculaMora recibe -años'.$intervalo->y.'meses '.$intervalo->m.' dias '.$intervalo->d.' total dias '.$intervalo->format('%a').' capital '.$ptecapital.' tasamora '.$tasamora);
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
    private function calIntMoraDias($dia,$tasamora,$salcapital){
        //se convierte los dias de mora a meses con fracciones
        $mes=($dia*100/30)/100;
        $int=$mes*($tasamora/100)*$salcapital;
        return round($int,2);
    }
    /////////////////////777


    public function canceled()
    {
        $credits = Credit::where('estado',0)->orderBy('id','ASC')->get();//->paginate(15);
        //$credits = Credit::all();
        $users = User::all('id','document','name','lastname');
        //dd($users);
        return view('admin.credits.canceled')
            ->with('credits',$credits)
            ->with('users',$users);
        dd('mostrar creditos en estado inactivo');
    }
    public function printPreview()
    {
        
    }

}
