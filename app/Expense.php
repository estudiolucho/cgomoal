<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table ="expenses";
    protected $fillable =['concept_id','expense_date','amount','description',];
    //protected $fillable =['expense_date','amount','description'];
    public function expense_concept(){
    	return $this->belongsTo('App\Expense_Concept','concept_id','id'); //asi me funciono
    	//return $this->belongsTo('App\Expense_Concept');
    }
}
