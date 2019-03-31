<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contribution_Concept extends Model
{
    protected $table ="contribution_concepts";
    protected $fillable =['concept','description','active',];
    public function contributions()
    {
    	return $this->hasMany('App\Contribution');
    }
}
