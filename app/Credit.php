<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    //
    protected $table = "credits";
    protected $fillable = [
    	'user_id','fecha_desembolso','valor_desembolso','tasa_mensual',
    	'cuotas','valor_cuota','saldo_interes','saldo_capital','estado','descripcion'
    	];
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
    public function payments()
    {
        return $this->hasMany('App\Payment','credit_id','id');
    }
    
}
