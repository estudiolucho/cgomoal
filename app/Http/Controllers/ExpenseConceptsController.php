<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Expense_ConceptRequest;
use Illuminate\Http\Request;
use App\Expense_Concept;
class ExpenseConceptsController extends Controller
{
    /**
     *Display a listing of the resource
     *
     *@return \Iluminate\Http\Response
     *
     */
    public function index(){
        $concepts = Expense_Concept::orderBy('id','DESC')->paginate(5);
        return view('admin.expenseconcepts.index')->with('econcepts',$concepts);
    }
    /**
     *Show the form for create a new resource
     *
     *@return \Iluminate\Http\Response
     *
     */

    public function create(){
    	return view('admin.expenseconcepts.create');
    }
    public function store(Request $request){
    	//dd($request->all());
        $concept= new Expense_Concept($request->all());
        $concept->user_create=Auth::user()->username;
        $concept->save();
        $text='Concepto De Gasto Creado';
        return view('admin.template.messages')->with('text',$text);
    }
    public function edit($id){
        $concept= Expense_Concept::find($id);
        return view('admin.expenseconcepts.edit')
            ->with('expense_concepts',$concept);
    }
    /**
     *Update the specified resource in storage
     *
     *@param \Illuminate\Http\Request $request
     *@param int $id
     *@return \Illuminate\Http\Response
     */
    public function update(ExpenseRequest $request,$id){
        $expense = Expense::find($id);
        /*$expense->concept_id = $request->concept_id;
        $expense->expense_date = $request->expense_date;
        $expense->amount = $request->amount;
        $expense->description = $request->description;*/
        $expense->fill($request->all()); //reemplaza las 4 lineas anteriores
        $now = new DateTime();
        $expense->updated_at = $now;
        $expense->user_create=Auth::user()->username;
        $expense->save();
        //dd($expense);
        return redirect()->route('expense.index');
    }
}
