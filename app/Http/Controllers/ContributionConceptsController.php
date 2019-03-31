<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Contribution_ConceptRequest;
use Illuminate\Http\Request;
use App\Contribution_Concept;
class ContributionConceptsController extends Controller
{
    //
    /**
     *Display a listing of the resource
     *
     *@return \Iluminate\Http\Response
     *
     */
    public function index(){
        $concepts = Contribution_Concept::orderBy('id','DESC')->paginate(5);
        return view('admin.contributionconcepts.index')->with('cconcepts',$concepts);
    }
    /**
     *Show the form for create a new resource
     *
     *@return \Iluminate\Http\Response
     *
     */
    public function create(){
    	return view('admin.contributionconcepts.create');
    }
    public function store(Request $request){
        $concept= new Contribution_Concept($request->all());
        $concept->user_create=Auth::user()->username;
        $concept->save();
        //dd('concepto creado');
        $text="Concepto de Aportes Creado";
        return view ('admin.template.messages')->with('text',$text);
    }
}
