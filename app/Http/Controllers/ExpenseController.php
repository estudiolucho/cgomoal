<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;

use App\Expense;
use App\Expense_Concept;
use App\Cash_Flow;
use DateTime;
use Excel;
use Session;


class ExpenseController extends Controller
{
    
    /**
     * Display A listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(){
        //return ('hola' );
        $expenses = Expense::orderBy('id','DESC')->paginate(20);
        $concepts = Expense_Concept::all('id','concept');
        return view('admin.expenses.index')
            ->with('expenses',$expenses)
            ->with('concepts',$concepts);
    }

    public function show($id){
        $expense= Expense::find($id); 
        $concept= Expense_Concept::all('id','concept')->where('id','=', $expense->concept_id)->last();
        return view('admin.expenses.show')->with('expense',$expense)->with('concept',$concept);
    }


    public function edit($id){
        //dd($id);
        $expense= Expense::find($id);
        $concepts = Expense_Concept::all('id','concept');
        return view('admin.expenses.edit')
            ->with('expense',$expense)
            ->with('expense_concepts',$concepts);
    }
    /**
     *Update the specified resource in storage
     *
     *@param \Illuminate\Http\Request $request
     *@param int $id
     *@return \Illuminate\Http\Response
     */
    public function update(ExpenseRequest $request,$id){
        //dd($request->all());
        $expense = Expense::find($id);
        /*$expense->concept_id = $request->concept_id;
        $expense->expense_date = $request->expense_date;
        $expense->amount = $request->amount;
        $expense->description = $request->description;*/
        $expense->fill($request->all()); //reemplaza las 4 lineas anteriores
        $now = new DateTime();
        $expense->updated_at = $now;
        //dd($expense);
        $expense->user_update=Auth::user()->username;
        $expense->save();
        //dd($expense);
        return redirect()->route('expense.index');
    }


    public function create(){
        //traer los conceptos de gastos
        //$concept = Expense_Concept::orderBy('id','ASC')->lists('concept');
        $concept = Expense_Concept::all('id','concept','active')->where('active','1');
        //dd($concept);
        return view('admin.expenses.create')->with('expense_concepts',$concept);
    }
    public function store(ExpenseRequest $request){
        //dd($request->all());
        $expense= new Expense($request->all());
        //dd($expense);
        $expense->user_create=Auth::user()->username;
        $expense->save();

        //inserto el registro para la tabla cash_flow
        $last_cash_flow= Cash_Flow::orderBy('id', 'DESC')->first();
        $cash_flow= new Cash_Flow();
        $cash_flow->date=\Carbon\Carbon::now();//podria ser $expense->expense_date;
        $cash_flow->amount=$expense->amount;
        $cash_flow->concept='gasto';
        $cash_flow->type='salida';
        $cash_flow->description="Gasto#".$expense->id."| Concepto ".Expense_Concept::find($expense->concept_id)->concept." | ".$expense->description;
        $cash_flow->balance=$last_cash_flow->balance+(-1*$expense->amount);
        $cash_flow->user_create=Auth::user()->username;
        $cash_flow->save();
        //return $this->index();
        return Redirect::route('expense.created');
    }
        // con  esta funcion se evita que al recargar F5  la pagina se inserte de nuevo el registro
    public function created(){
        return $this->index();
    }

    ///*
    //se pasa como parametro el id de un gasto
    public function view($id)
    {
    	$expense= Expense::find($id);
    	//$expense->Expense_Concept;
    	$expense->expense_concept;
    	//dd($expense); //muestra el gasto sin formatear
		return view('expense',[ 'expense'=> $expense]); //'expense' esla variable donde recibe la vista


    }//*/
    //se pasa como parametro el id de un concepto de gasto 
    public function view2($id)
    {
    	$expense2= Expense_Concept::find($id);
    	$expense2->expenses;
    	dd($expense2);

    }

    public function search(){
        return view('reports.expensesearch');
    }public function listByDate(Request $request){
        $date1=$request->startdate;
        $date2=$request->enddate;
        Session::put('date1', $date1);
        Session::put('date2', $date2);
        //$q = Input::get ( 'q' );
        //if($q != ""){
            //$user = User::where ( 'created_at', 'LIKE', '%' . $q . '%' )->orWhere ( 'created_at', 'LIKE', '%' . $q . '%' )->paginate (5)->setPath ( '' );
        $expense_concepts= Expense_Concept::all('id','concept','active')->where('active',1);
        $expenses = Expense::whereBetween('created_at', array($date1, $date2))->get();//->first();
        //$pagination = $payments->appends ( array ('q' => Input::get ( 'q' ) ) );
        //}
        return view('reports.expensesbydate')->with('expenses',$expenses)->with('id','Desde: '.$date1.' Hasta: '.$date2)->with('expense_concepts',$expense_concepts);
    }
        public function downloadExcel($type)
    {
        //dd( Session::get('date1'),Session::get('date2'));
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.

        $expenses = Expense::join('expense_concepts', 'expense_concepts.id','=', 'expenses.concept_id')
        ->select(
          'expenses.id', 
          'expense_concepts.concept', 
          'expenses.expense_date',
          'expenses.created_at',
          'expenses.amount', 
          'expenses.description')
        ->whereBetween('expenses.created_at', array(Session::get('date1'),Session::get('date2')))
        ->get();
        // Initialize the array which will be passed into the Excel
        // generator.
        $expensesArray = []; 
        // Define the Excel spreadsheet headers
        $expensesArray[] = ['Num Gasto','Concepto','Fecha','Fecha Creacion','Valor','descripcion'];
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($expenses as $expense) {
        $expensesArray[] = $expense->toArray();
        }
        $this->generaExcel($expensesArray,'Gastos');
    }
    private function generaExcel($datos,$tabla){
        //esto es igual en todos sepuede poner en una funcion
        // Generate and return the spreadsheet 
        Excel::create( $tabla.Session::get('date1').'a'.Session::get('date2'), function($excel) use ($datos) {
            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Gastos');
            $excel->setCreator('Gomoa')->setCompany('FondoGomoa');
            $excel->setDescription('Gastos Realizados');
            // Build the spreadsheet, passing in the payments array
            $excel->sheet('Gastos', function($sheet) use ($datos) {
                $sheet->fromArray($datos, null, 'A1', false, false);
            });
        })->download('xlsx');
    }
}
