<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
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
            'concept_id'=>'required',
            'amount'=> 'min:2|max:10|required',
            'description'=> 'min:5|max:250|required'
            
            //,'email'=> 'min:2|max:5|unique:users|required'
            //,'password'=> 'min:5|max:15|required'
            //,'document_id'=> 'min:5|max:15|required|unique:users'
            //
        ];
    }
}
