<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    protected $table ="contributions";
    protected $fillable =[
    	'user_id','concept_id','contribution_date','amount','description'
    ];
    public function contribution_concept()
    {
    	return $this->belongsTo('App\Contribution_Concept');
    }
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
