<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ContributionRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\User;
use App\Contribution_Concept;
use App\Contribution;
use App\Cash_Flow;
use DateTime;
use Excel;
use Session;
class ContributionController extends Controller
{
    public function index(){
        $contributions = Contribution::orderBy('id','DESC')->paginate(10);
        //$contributions = Contribution::orderBy('id','DESC')->take(10)->get();
        $concepts = Contribution_Concept::all('id','concept');
        $users = User::all('id','document','name','lastname');
        return view('admin.contributions.index')
            ->with('contributions',$contributions)
            ->with('concepts',$concepts)
            ->with('users',$users);
    }
    //muestra detalle de un aporte
    public function show($id){
        $contribution= Contribution::find($id); 
        //tambien sirve $contribution = Contribution::where('id','=', $id)->first();
        //$user = User::where('id','=', $contribution->user_id)->first(); //trae todo el registro como entidad
        $user = User::all('id','document','name','lastname')->where('id','=', $contribution->user_id)->last(); //trae solo los campos 
        $concept= Contribution_Concept::all('id','concept')->where('id','=', $contribution->concept_id)->last();
        //dd($user);
        return view('admin.contributions.show')->with('contribution',$contribution)->with('user',$user)->with('concept',$concept);
        //dd($contribution,$concept,$user);
    }

    public function edit($id){
        //dd($id);
        $contribution= Contribution::find($id);
        $concepts = Contribution_Concept::all('id','concept');
        $user = User::all('id','document','name','lastname')->where('id','=', $contribution->user_id)->last(); //trae solo los campos 
        return view('admin.contributions.edit')
            ->with('contribution',$contribution)
            ->with('contribution_concepts',$concepts)
            ->with('user',$user);
    }public function update(ContributionRequest $request,$id){
        //dd($request->all());
        $contribution = Contribution::find($id);
        $contribution->fill($request->all());
        $now = new DateTime();
        $contribution->updated_at = $now;
        $contribution->user_update=Auth::user()->username;
        $contribution->save();
        //dd($expense);
        return redirect()->route('contribution.index');
    }
    //crear aporte
    public function create(){
        //traer los conceptos de gastos
        //$concept = Expense_Concept::orderBy('id','ASC')->lists('concept');
        $concept = Contribution_Concept::all('id','concept','active')->where('active',1);
        $user = User::all('id','document','type')->where('type','socio');
        //dd($user);
        return view('admin.contributions.create')
        	->with('contribution_concepts',$concept)
        	->with('users',$user);
        //return view('admin.expenses.create','expense_concepts'=>$concept);
    }public function store(ContributionRequest $request){
        Log::info('ContributionContr: Metodo Store');
        $contribution= new Contribution($request->all());
        $user= User::find($request->user_id);
        $contribution->user_create=Auth::user()->username;
        $contribution->save();
        Log::info('Contribution Creado: '.$contribution);
         //inserto el registro para la tabla cash_flow
        $last_cash_flow= Cash_Flow::orderBy('id', 'DESC')->first();
        $cash_flow= new Cash_Flow();
        $cash_flow->date=\Carbon\Carbon::now();//podria ser $contribution->contribution_date;
        $cash_flow->amount=$contribution->amount;
        $cash_flow->concept='aporte';
        $cash_flow->type='entrada';
        $cash_flow->description="Aporte#".$contribution->id."| C.C. ".User::find($contribution->user_id)->document." | ".Contribution_Concept::find($contribution->concept_id)->concept." | ".$contribution->description;
        $cash_flow->balance=$last_cash_flow->balance+$contribution->amount;
        $cash_flow->user_create=Auth::user()->username;
        $cash_flow->save();
        Log::info('Cash_Flow Creado: '.$cash_flow);
        return Redirect::route('contribution.created');//evita que al recargar se duplique el registro
    }public function created(){
        return $this->index();
    }

    //retiro de aportes
    public function retirement(){
        $concept = Contribution_Concept::all('id','concept','active')->where('active',1);
        $user = User::all('id','document','type')->where('type','socio');
        return view('admin.contributions.retirement')
            ->with('contribution_concepts',$concept)
            ->with('users',$user);
    }public function storeRetirement(ContributionRequest $request){
        Log::info('ContributionContr: Metodo storeRetirement');
        //validar que el valor a retirar no supere los aportes del socio
        $user_id=$request->user_id;
        $user= User::find($request->user_id);
        $contributions=Contribution::where('user_id',$user_id)->get();
        $totalaportes=0;
        foreach ($contributions as $contribution) {
            $totalaportes=$totalaportes+$contribution->amount;
        }
        if($totalaportes >= $request->amount){
            Log::info('Aportes >  Retiro');
            $contribution= new Contribution($request->all());
            $contribution->amount=(-1)*$request->amount;
            $contribution->description='*RETIRO DE FONDOS* '.$request->description;
            $contribution->user_create=Auth::user()->username;
            $contribution->save();
            Log::info('Contribution *Retiro* Creado: '.$contribution);
            
             //inserto el registro para la tabla cash_flow
            $last_cash_flow= Cash_Flow::orderBy('id', 'DESC')->first();
            $cash_flow= new Cash_Flow();
            $cash_flow->date=\Carbon\Carbon::now();//podria ser $contribution->contribution_date;
            $cash_flow->amount=$contribution->amount;
            $cash_flow->concept='aporte';
            $cash_flow->type='salida';
            $cash_flow->description="Aporte#".$contribution->id."| C.C. ".User::find($contribution->user_id)->document." | ".Contribution_Concept::find($contribution->concept_id)->concept." | ".$contribution->description;
            $cash_flow->balance=$last_cash_flow->balance+$contribution->amount;
            $cash_flow->user_create=Auth::user()->username;
            $cash_flow->save();
            Log::info('Cash_Flow *Retiro* Creado: '.$cash_flow);
            return Redirect::route('contribution.created');//evita que al recargar se duplique el registro
        }else{
            $text='El socio '.$user->name.' '.$user->lastname.' no tiene fondos suficientes';
            return view('admin.template.messages')->with('text',$text);
        }
    }

    public function findnousar(Request $request){
        $doc= $request->input('document');
        if ($doc){
            $date1=$request->startdate;
            $date2=$request->enddate;
            Session::put('user_id', $user_id);
            Session::put('date1', $date1);
            Session::put('date2', $date2);
            return $this->posfind(Crypt::encrypt($doc));
        }else{
            $text='Informacion invalida, Verifique';
            return view('admin.template.messages')->with('text',$text);
        }
    //aportes por documento de socio 
    }public function find($doc){
        $doc=Crypt::decrypt($doc);

        $user = User::where('type','=','socio')->where('document','=', $doc)->first();
        if($user){
            $contribution_concepts= Contribution_Concept::all('id','concept','active')->where('active',1);
            $contributions= Contribution::where('user_id',$user->id)->get();
            return view('reports.contributionsbydocument')
            ->with('contribution_concepts',$contribution_concepts)
            ->with('contributions',$contributions)
            ->with('id',$user->document)
            ->with('user',$user);
        }else{
            $text='el numero de cedula ingresado no existe';
            return view('admin.template.messages')->with('text',$text);
        }
    }

    //aportes por lapso de fechas
    public function search(){
        return view('reports.contributionsearch');
    }public function listByDate(Request $request){
        $date1=$request->startdate;
        $date2=$request->enddate;
        Session::put('date1', $date1);
        Session::put('date2', $date2);
        $q = Input::get ( 'q' );
        $user = User::all('id','document','type','name')->where('type','socio');
        $contribution_concepts= Contribution_Concept::all('id','concept','active')->where('active',1);
        $contribution = Contribution::whereBetween('created_at', array($date1, $date2))->orderBy('user_id','ASC')->get();

        //$test= DB::table('contributions')->select(DB::raw('user_id'),DB::raw('concept_id'),DB::raw('sum(amount) as total'))->whereBetween('created_at',array($date1, $date2))->groupBy(DB::raw('concept_id'))->groupBy(DB::raw('user_id'))->get();

        //return $this->group($contribution);
        //dd($contribution,$date1, $date2);
        return view('reports.contributionsbydate')->with('contributions',$contribution)->with('id','Desde: '.$date1.' Hasta: '.$date2)->with('user',$user)->with('contribution_concepts',$contribution_concepts);
    }public function downloadExcel($type){
        //dd( Session::get('date1'),Session::get('date2'));
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.

        $contributions = Contribution::join('users', 'users.id', '=', 'contributions.user_id')->join('contribution_concepts', 'contribution_concepts.id','=', 'contributions.concept_id')
        ->select(
          'contributions.id', 
          'users.document', 
          \DB::raw("concat(users.name, ' ', users.lastname) as `name`"), 
          'contribution_concepts.concept', 
          'contributions.created_at',
          'contributions.amount', 
          'contributions.description')
        ->whereBetween('contributions.created_at', array(Session::get('date1'),Session::get('date2')))
        ->get();
        // Initialize the array which will be passed into the Excel
        // generator.
        $contributionsArray = []; 
        // Define the Excel spreadsheet headers
        $contributionsArray[] = ['Num Aporte','Cedula', 'Usuario','Concepto','Fecha','monto','description'];
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($contributions as $contribution) {
        $contributionsArray[] = $contribution->toArray();
        }
        $this->generaExcel($contributionsArray,'Aportes');
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
    }
    
    public function group($contribution){
        dd($contribution);
    }
    
    //aportes por lapso agrupados por usuario-concepto
    public function search2(){
        return view('reports.contributionsearch2');
    }public function grouplistByUser(Request $request){
        $date1=$request->startdate;
        $date2=$request->enddate;
        Session::put('date1', $date1);
        Session::put('date2', $date2);
        $q = Input::get ( 'q' );
        $user = User::all('id','document','name','type')->where('type','socio');
        $contribution_concepts= Contribution_Concept::all('id','concept','active')->where('active',1);
        $contribution= DB::table('contributions')->select(DB::raw('user_id'),DB::raw('concept_id'),DB::raw('sum(amount) as total'))->whereBetween('created_at',array($date1, $date2))->groupBy(DB::raw('user_id'))->groupBy(DB::raw('concept_id'))->get();
        return view('reports.contributionsgroupbyuser')->with('contributions',$contribution)->with('id','Desde: '.$date1.' Hasta: '.$date2)->with('user',$user)->with('contribution_concepts',$contribution_concepts);
    }public function downloadExcel2($type){
        $contributions = Contribution::join('users', 'users.id', '=', 'contributions.user_id')->join('contribution_concepts', 'contribution_concepts.id','=', 'contributions.concept_id')
        ->select(
          'users.document', 
          \DB::raw("concat(users.name, ' ', users.lastname) as `name`"), 
          'contribution_concepts.concept',
          \DB::raw('SUM(contributions.amount) as total'))
        ->whereBetween('contributions.created_at', array(Session::get('date1'),Session::get('date2')))
        ->groupBy('contributions.user_id')->groupBy('contributions.concept_id')
        ->get();
        // Initialize the array which will be passed into the Excel
        // generator.
        $contributionsArray = []; 
        // Define the Excel spreadsheet headers
        $contributionsArray[] = ['Cedula', 'Usuario','Concepto','monto'];
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($contributions as $contribution) {
        $contributionsArray[] = $contribution->toArray();
        }
        $this->generaExcel($contributionsArray,'Aportes');
    }
    //aportes por lapso-socio
    public function search3(){
        $user = User::all('id','document','type')->where('type','socio');
        return view('reports.contributionsearch3')->with('users',$user);;
    }public function listByDocument(Request $request){
        $user_id=$request->user_id;
        $date1=$request->startdate;
        $date2=$request->enddate;
        Session::put('user_id', $user_id);
        Session::put('date1', $date1);
        Session::put('date2', $date2);
        //$q = Input::get ( 'q' );//no se necesita
        $user = User::all('id','document','type')->where('id',$user_id);
        $contribution_concepts= Contribution_Concept::all('id','concept','active')->where('active',1);
        $contribution = Contribution::where('user_id','=',$user_id)->whereBetween('created_at', array($date1, $date2))->orderBy('user_id','ASC')->paginate();;
        return view('reports.contributionsbewteendatebydocument')->with('contributions',$contribution)->with('id','Desde: '.$date1.' Hasta: '.$date2)->with('user',$user)->with('contribution_concepts',$contribution_concepts);
    }public function downloadExcel3($type){
        $contributions = Contribution::join('users', 'users.id', '=', 'contributions.user_id')->join('contribution_concepts', 'contribution_concepts.id','=', 'contributions.concept_id')
        ->select(
          'contributions.id', 
          'users.document', 
          \DB::raw("concat(users.name, ' ', users.lastname) as `name`"), 
          'contribution_concepts.concept', 
          'contributions.created_at',
          'contributions.amount', 
          'contributions.description')
        ->where('user_id','=',Session::get('user_id'))
        ->whereBetween('contributions.created_at', array(Session::get('date1'),Session::get('date2')))
        ->get();
        // Initialize the array which will be passed into the Excel
        // generator.
        $contributionsArray = []; 
        // Define the Excel spreadsheet headers
        $contributionsArray[] = ['Num Aporte','Cedula', 'Usuario','Concepto','Fecha','monto','descripcion'];
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($contributions as $contribution) {
        $contributionsArray[] = $contribution->toArray();
        }
        $this->generaExcel($contributionsArray,'Aportes');
    }

    //aportes por socio
    public function search4(){
        $user = User::all('id','document','type')->where('type','socio');
        return view('reports.contributionsearch4')->with('users',$user);;
    }public function listDocument(Request $request){
        $user_id=$request->user_id;
        Session::put('user_id', $user_id);
        //$q = Input::get ( 'q' );//no se necesita
        $user = User::all('id','document','name','lastname','type')->where('id',$user_id)->first();
        $contribution_concepts= Contribution_Concept::all('id','concept','active')->where('active',1);
        $contribution = Contribution::where('user_id','=',$user_id)->orderBy('id','ASC')->paginate();;
        return view('reports.contributionsbydocument')->with('contributions',$contribution)->with('id',$user->name.' '.$user->lastname)->with('user',$user)->with('contribution_concepts',$contribution_concepts);
    }public function downloadExcel4($type){
        $contributions = Contribution::join('users', 'users.id', '=', 'contributions.user_id')->join('contribution_concepts', 'contribution_concepts.id','=', 'contributions.concept_id')
        ->select(
          'contributions.id', 
          'users.document', 
          \DB::raw("concat(users.name, ' ', users.lastname) as `name`"), 
          'contribution_concepts.concept', 
          'contributions.created_at',
          'contributions.amount', 
          'contributions.description')
        ->where('user_id','=',Session::get('user_id'))->get();
        // Initialize the array which will be passed into the Excel
        // generator.
        $contributionsArray = []; 
        // Define the Excel spreadsheet headers
        $contributionsArray[] = ['Num Aporte','Cedula', 'Usuario','Concepto','Fecha','monto','descripcion'];
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($contributions as $contribution) {
        $contributionsArray[] = $contribution->toArray();
        }
        $this->generaExcel2($contributionsArray,'Aportes');
    }private function generaExcel2($datos,$tabla){
        $ced=User::find(Session::get('user_id'));
        Excel::create( $tabla.'Usuario-'.$ced->document, function($excel) use ($datos) {
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

    //recibe un array de los registros a exportar en excel
    private function generaExcel($datos,$tabla){
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


    //pendiente terminar
    public function balanceByUser(Request $request){
        //el request viene con fecha inicial y  fecha final
        //definir la fecha inicio y final del balance
        //traer los usuarios tipo socio y ponerlos en un array 
        //recorrer el array de usuarios
        //por cada usuario consultar los aportes realizados en el lapso de fechas
        //recorrer los aportes y totalizarlos en una variable vraporteslapso
        //consultar los registros de tabla balance que tengan el usuario ordenado DES y traer el ultimo
        //calcular el saldofin tomando el saldo final del ultimo registro + vraporteslapso 
        //crear el registro en tabla balance con user_id, lapsoini,lapsofin, vraporteslapso, saldofin
    }
    public function importExport()
    {
        return view('importExport');
    }
    public function importExcel()
    {
        if(Input::hasFile('import_file')){
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get();
            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    $insert[] = ['title' => $value->title, 'description' => $value->description];
                }
                if(!empty($insert)){
                    DB::table('items')->insert($insert);
                    dd('Insert Record successfully.');
                }
            }
        }
        return back();
    }
}
