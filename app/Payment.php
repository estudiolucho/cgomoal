<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = "payments";
    protected $fillable = ['credit_id','document','date_payment','amount','descripcion','intmora' ,'abono_interes','saldo_interes','saldo capital',
    	'abono_capital','created_at'];
    public function credit()
    {
    	return $this->belongsTo('App\Credit','credit_id','id');
    }
}
