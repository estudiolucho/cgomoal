<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //'user_id'=> 'min:1|max:5|required',
            'valor_desembolso'=> 'min:3|max:10|required',
            'tasa_mensual'=> 'min:1|max:4|required',
            'fecha_desembolso'=>'required',
            'cuotas'=> 'min:1|max:3|required',
            'saldo_capital'=>'numeric',
            'descripcion'=> 'min:5|max:120|required'
            //
        ];
    }
}
