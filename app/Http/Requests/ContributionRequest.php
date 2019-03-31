<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContributionRequest extends FormRequest
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
            'concept_id'=> 'required',
            'user_id'=> 'required',
            'amount'=> 'min:1|max:10|required',
            'description'=> 'min:7|max:255|required'
            //
        ];
    }
}
       
