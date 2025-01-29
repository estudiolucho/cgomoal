<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cash_Flow extends Model
{
    protected $table = "cash_flow";
    protected $fillable = ['id','date','amount','concept','type','description' ,'balance','user_create','user_update','created_at'];
//comment
}
