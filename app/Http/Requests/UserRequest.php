<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'document'=> 'min:6|max:11|required',
            'username'=> 'min:5|max:20|required|unique:users,id,'.$this->get('id'),
            'password'=> 'min:4|max:20|required|unique:users,id,'.$this->get('id'),
            'role_id'=> 'required',
            'type'=> 'required',
            'email'=> 'min:2|max:100|required|unique:users,id,'.$this->get('id'),
            'main_addr'=> 'min:8|max:200|required|unique:users,id,'.$this->get('id'),
            //'referrer'=> 'min:5|max:15|required',
            'main_phone'=> 'min:7|max:20|required'
        ];
    }
}
