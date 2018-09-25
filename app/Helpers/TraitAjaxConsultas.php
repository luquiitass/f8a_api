<?php


namespace App\Helpers;


use App\Models\Util\AjaxQuery;
use App\Models\Util\ReturnJSON;

/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 17/09/18
 * Time: 14:07
 */
trait TraitAjaxConsultas
{

    use TraitValidation;


    public function getObject($clase, $id){

            $object = AjaxQuery::newObject($clase,$id);

            return ReturnJSON::success(array($clase => $object));

    }

    public function getCollection($clase){
        $collection = AjaxQuery::newModel($clase)->get();

        return ReturnJSON::success(array('all'=>$collection));
    }



    public function reloadAttribute($clase ,$id,$attribute){

            $object = AjaxQuery::newObject($clase,$id);

            return ReturnJSON::success([$clase=> [$attribute => $object->$attribute] ]);
    }

    public function paginate($clase){

        $collection = AjaxQuery::newModel($clase)->paginate(2);

        return  ReturnJSON::success([$clase =>$collection]);
    }


}