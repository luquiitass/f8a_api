<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserStoreRequest extends Request
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
            'email' => 'required|email|unique:users,email',
            //'photo' => 'required',
            'first_name'=>'required|min:3',
            'last_name'=>'required|min:3',
            'password'=>'required|confirmed|min:6'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'El campo Nombre es obligatorio.',
            'last_name.required' => 'El campo Apellido es obligatorio.',
            'password.required' => 'El campo Contraseña es obligatorio..'

        ];
    }
}
