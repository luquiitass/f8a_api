<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PlayerStoreRequest extends Request
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
            'name'=>'required|min:3',
            'position_id' => 'required',
            'birth_date' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'position_id.required' => 'Debe seleccionar la posición que tendrá el jugador',
            'birth_date.required' => 'Debe ingresar la fecha de nacimiento.'
        ];
    }
}
