<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document','name','lastname','role_id','username', 'email', 'password', 'type','main_addr','main_phone','secondary_addr','secondary_phone','referrer','active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function role(){
        return $this->belongsTo('App\Role','role_id','id');
    }
    public function credits()
    {
        return $this->hasMany('App\Credit');
    }
    public function isAdmin(){
        return $this->role_id === 1;
    }
    public function isOperator(){
        return $this->role_id === 2;
    }
    public function isUser(){
        return $this->role_id === 3;
    }
    public function isSocio(){
        return $this->type === 'socio';
    }
}
