<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Requests\Cash_FlowRequest;
use App\Cash_Flow;
use App\Credit;
use Excel;
use Session;
use DateTime;

class CashFlowController extends Controller
{
    public function index()
    {
        return view('admin.cashflow.index');
    }
    public function show(){}
    public function create(){
        return view('admin.cashflow.create');

    }public function store(Cash_FlowRequest $request){
        $cashflow= new Cash_Flow($request->all());
        $cashflow->user_create=Auth::user()->username;
        $last= Cash_Flow::orderBy('id', 'DESC')->first();
        if($cashflow->type == 'entrada'){
            if($cashflow->concept == 'abono' || $cashflow->concept == 'ajuste_ent'|| $cashflow->concept == 'aporte'){
            $cashflow->balance=$last->balance+$cashflow->amount;
            }else{
                $text='no coincide el concepto con el tipo';
                return view('admin.template.messages')->with('text',$text);
            }
        }else{
            if($cashflow->concept == 'credito' || $cashflow->concept == 'gasto'|| $cashflow->concept == 'ajuste_sal'|| $cashflow->concept == 'aporte'){
            $cashflow->balance=$last->balance-$cashflow->amount;
            }else{
                $text='no coincide el concepto con el tipo';
                return view('admin.template.messages')->with('text',$text);
            }
        }
        $cashflow->save();
        return Redirect::route('cashflow.created');
    }
        // con  esta funcion se evita que al recargar F5  la pagina se inserte de nuevo el registro
    public function created(){
        return view('admin.cashflow.index');
        //return $this->index();
    }

    public function select(){
        return view('admin.cashflow.select');
    }public function editar(Request $request){
        $id=$request->id;
        $cashflow= Cash_Flow::find($id);
        if($cashflow){
        return view('admin.cashflow.edit')->with('cashflow',$cashflow);
        }else{
            $text='el Registro '.$id.' no se encuentra.';
            return view('admin.template.messages')->with('text',$text);
        }
    }public function update(Cash_FlowRequest $request,$id){
        $cashflow= Cash_Flow::find($id);
        $cashflow->fill($request->all());
        $cashflow->user_update=Auth::user()->username;
        $now = new DateTime();
        $cashflow->updated_at= $now;
        if($cashflow->type == 'entrada'){
            if($cashflow->concept == 'abono' || $cashflow->concept == 'ajuste_ent'|| $cashflow->concept == 'aporte'){
            }else{
                $text='no coincide el concepto con el tipo';
                return view('admin.template.messages')->with('text',$text);
            }
        }else{
            if($cashflow->concept == 'credito' || $cashflow->concept == 'gasto'|| $cashflow->concept == 'ajuste_sal'|| $cashflow->concept == 'aporte'){
            }else{
                $text='no coincide el concepto con el tipo';
                return view('admin.template.messages')->with('text',$text);
            }
        }
        $cashflow->save();
        return view('admin.cashflow.index');
    }

    public function delete(){
        return view('admin.cashflow.delete');
    }public function search(Request $request){
        $cashflowid=$request->cashflow_id;
        $creditid=$request->credit_id;
        $First = "#";
        $Second = "|";
        $cashflow=Cash_Flow::find($cashflowid);
        //pte validar cuando no existe el $cashflowid
        if($cashflow!=null){
            $posini=strpos($cashflow->description, $First)+strlen($First);
            $posfin=strpos($cashflow->description, $Second)+strlen($Second);
            $substring=substr($cashflow->description, $posini,(int)$posfin-(int)$posini-1);
            Log::info('posinicio '.$posini.' posfinal '.$posfin.' substing '.$substring);
            $ult=Cash_Flow::orderBy('id','DESC')->take(1)->get();
            if ((int)$ult[0]->id==$cashflowid){
                if((int)$substring==$creditid){
                    $credit=Credit::find($creditid);
                    Log::warning('Borrando Registro de Cash_Flow'.$cashflow.' Usuario Realiza: '.Auth::user()->username);
                    Log::warning('Borrando Registro de Credit'.$credit.' Usuario Realiza:'.Auth::user()->username);
                    $credit->delete();
                    $cashflow->delete();
                    $text='Movimiento de caja y credito eliminados';
                    return view('admin.template.messages')->with('text',$text);
                }else {
                    $text='El Número  de credito no corresponde al movimiento de caja, por favor verifique';
                    return view('admin.template.messagesw')->with('text',$text);
                }
            }else{
                $text='Solo el  ultimo movimiento de caja se puede eliminar. Comuniquese con el Administrador';
                return view('admin.template.messagesw')->with('text',$text);
                dd('NO es el ultimo NO puedo borrar');
            }
        
        
        }else{
            $text='No Existe Movimiento de Caja, Verifique';
            return view('admin.template.messagesw')->with('text',$text);
            dd('NO es el ultimo NO puedo borrar');
        }
        
        
        //if($cashflow->description)
    }public function getStringBetween($str,$from,$to){
        $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
        return substr($sub,0,strpos($sub,$to));
    }

    public function list(Request $request)
    {
        $date1=$request->startdate;
        $date2=$request->enddate;
        Session::put('date1', $date1);
        Session::put('date2', $date2);
        $cash_flow = Cash_Flow::whereBetween('created_at', array($date1, $date2))->get();
        //$cash_flow = Cash_Flow::orderBy('id','DESC')->get();
        return view('admin.cashflow.list')
            ->with('cash_flow',$cash_flow);
    }
    public function downloadExcel($type)
    {
        //dd( Session::get('date1'),Session::get('date2'));
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.

        $cashflow = Cash_Flow::select(
          'id', 
          'date', 
          'concept', 
          'type', 
          'amount',
          'balance', 
          'description',
          'user_create')
        ->whereBetween('cash_flow.date', array(Session::get('date1'),Session::get('date2')))
        ->get();
        // Initialize the array which will be passed into the Excel
        // generator.
        $cashflowArray = []; 
        // Define the Excel spreadsheet headers
        $cashflowArray[] = ['Num','Fecha', 'Concepto','Tipo','Valor','Balance','description','Elaboro'];
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($cashflow as $cash) {
        $cashflowArray[] = $cash->toArray();
        }
        $this->generaExcel($cashflowArray,'RegistroDiario');
        /*linea anterior es lo mismo que este bloque
            // Generate and return the spreadsheet
            Excel::create('contribuciones', function($excel) use ($contributionsArray) {
                // Set the spreadsheet title, creator, and description
                $excel->setTitle('Aportes');
                $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
                $excel->setDescription('paymentsfile');
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('sheet1', function($sheet) use ($contributionsArray) {
                    $sheet->fromArray($contributionsArray, null, 'A1', false, false);
                });
            })->download('xlsx');
        */
        /* OTRA FORMA QUE  ENCONTRE DE HACERLO

        $data = Contribution::get()->toArray();
        //$data = Contribution::all('id')->orderBy('user_id','ASC')->toArray();

        return Excel::create('LaravelExcel', function($excel) use ($data){
            $excel->sheet('hojaExcel', function($sheet) use ($data){
                $sheet->setOrientation('landscape')->fromArray($data);
            });
        })->export('xls');

        return Excel::create('archivoejemplo', function($excel) use ($data) {
            $excel->sheet('hojaarchivo', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
        */
    }private function generaExcel($datos,$tabla){
        //esto es igual en todos sepuede poner en una funcion
        // Generate and return the spreadsheet 
        Excel::create( $tabla.Session::get('date1').'a'.Session::get('date2'), function($excel) use ($datos) {
            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Aportes');
            $excel->setCreator('Gomoa')->setCompany('FondoGomoa');
            $excel->setDescription('Aportes Socios');
            // Build the spreadsheet, passing in the payments array
            $excel->sheet('Aportes', function($sheet) use ($datos) {
                $sheet->fromArray($datos, null, 'A1', false, false);
            });
        })->download('xlsx');
    }

}
