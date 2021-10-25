<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BalanceSheetStoreRequest extends Request
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
        $team_id = request('team_id');
        return [
            'user_id' => 'required|unique:balance_sheets,user_id,NULL,id,team_id,' . $team_id ,
            'team_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_id.unique' => 'Este usuario ya ha sido registrado.',
        ];
    }
}
