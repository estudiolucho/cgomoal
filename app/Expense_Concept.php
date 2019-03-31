<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense_Concept extends Model
{
    protected $table ="expense_concepts";
    protected $fillable =['concept','description','active',];
    /*
    * Get the expenses for the expense_concept
    */
    public function expenses(){
    	return $this->hasMany('App\Expense', 'concept_id','id'); //asi me funciono
    	//return $this->hasMany('App\Expense');  //explicacion de videos
    }
}
