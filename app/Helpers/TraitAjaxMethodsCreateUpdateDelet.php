<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 05/09/18
 * Time: 02:12
 */

namespace App\Helpers;


use App\Models\Util\ReturnJSON;

trait TraitAjaxMethodsCreateUpdateDelet
{

    public function methods($clase,$funcion){


            return $this->$funcion($clase);

    }

    public function create($className){

        $inputs = request()->all();

        $this->validarRequest($className,"Store");

        $model = $this->getExistClassModel($className);

        $object = $model::create($inputs);

        return ReturnJSON::successStore(array($className => $object ));

    }

    public function update($className){

        $inputs = request()->all();

        $this->validarRequest($className,"Update");

        $model = $this->getExistClassModel($className);

        $object = $this->getExistObject($model);

        $object->update($inputs);

        return ReturnJSON::successUpdate(array($className => $object ));


    }

    public function delete($className){

        $model = $this->getExistClassModel($className);

        $object = $this->getExistObject($model);

        $object->delete();

        return ReturnJSON::success(array($className => $object ));

    }


    public function getExistObject($model){

        if (request()->exists('id')){

            $id = request()->id;

            $object = $model::find($id);

            if ($object){

                return $object;

            }else{

                throw new \Exception("Este Registro ya no existe");
            }


        }else{

            throw new \Exception("no existe el id en REQUEST");

        }
    }

    public function getExistClassModel($clase){

        $clase =  'App\Models\\' . $clase;

        if (class_exists($clase)) {

            return  $clase;

        }else{

            throw new \Exception("No existe el Modelo " . $clase);

        }
    }

}