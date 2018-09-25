<?php

namespace App\Http\Controllers\Api;

use App\Helpers\TraitAjaxConsultas;
use App\Helpers\TraitAjaxMethodsCreateUpdateDelet;

class AjaxPeticiones extends ApiController
{
    //

    use TraitAjaxConsultas,TraitAjaxMethodsCreateUpdateDelet;

    public function index(){
        return response()->json("Bienvenido a la api");
    }
}
