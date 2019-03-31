<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Contribution_ConceptRequest extends FormRequest
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
            'concept'=> 'min:5|max:150|required|unique:contribution_concepts',
            'description'=> 'min:5|max:250|required'//
        ];
    }
}
