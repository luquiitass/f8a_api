<?php

namespace App\Http\Requests;

use App\Helpers\TraitCategory;
use App\Http\Requests\Request;

class TeamStoreRequest extends Request
{
    use TraitCategory;
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
            'name'=>'required|min:3|unique:teams,name,NULL,id,category_id,' . $this->getCategoryId()
        ];
    }
}
