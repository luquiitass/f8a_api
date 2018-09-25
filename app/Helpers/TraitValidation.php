<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 17/09/18
 * Time: 15:33
 */

namespace App\Helpers;


use App\Exceptions\ExepcionValidaciones;

trait TraitValidation
{

    public function validarRequest($clase,$method){

        $nameClassRequest = $clase .$method . "Request";

        $classRequest = $this->getExistClassRequest($nameClassRequest);

        $formRequest = new  $classRequest();

        if (! $formRequest->authorize()){
            throw new \Exception("Este usuario no posee permisos para realizar ésta operación");
        }

        $v = \Validator::make(request()->all(),$formRequest->rules(),$formRequest->messages());

        if ($v->fails()){
            throw new ExepcionValidaciones($v->errors());
        }

    }


    public function getExistClassRequest($clase){

        $clase =  'App\Http\Requests\\' . $clase;

        if (class_exists($clase)) {

            return  $clase;

        }else{

            throw new \Exception("Debe el Request para la validacion de  " . $clase);

        }
    }

}