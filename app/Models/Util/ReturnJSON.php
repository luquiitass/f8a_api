<?php

namespace App\Models\Util;

class ReturnJSON
{

    public static function success($arry){
        $retorno = array();
        $retorno["estado"]="success";
        $retorno = array_merge($retorno,$arry);
        $json = response()->json($retorno);
        if (Utilidades::isApi()){
            return $json;
        }
        return $json->getData();

    }

    public static function successStore($arry){
        $retorno = array();
        $retorno["estado"]="success";
        $retorno["msjSuccess"]="Registrado correctamente";
        $retorno = array_merge($retorno,$arry);
        $json = response()->json($retorno);
        if (Utilidades::isApi()){
            return $json;
        }
        return $json->getData();

    }

    public static function successUpdate($arry){
        $retorno = array();
        $retorno["estado"]="success";
        $retorno["msjSuccess"]="Modificado correctamente";
        $retorno = array_merge($retorno,$arry);
        $json = response()->json($retorno);
        if (Utilidades::isApi()){
            return $json;
        }
        return $json->getData();

    }

    public static function errorServer(\Exception $error){
        //\Log::error("Error Server",$error->getMessage());
        $retorno = array();
        $retorno["estado"]="error";
        $retorno["mensaje"]=$error->getMessage();
        return response()->json($retorno);
    }


    public static function error($msj){
        $retorno = array();
        $retorno["estado"]="success";
        $retorno["msjError"]=$msj;
        $json = response()->json($retorno);
        if (Utilidades::isApi()){
            return $json;
        }
        return $json->getData();

    }

    public static function errorValidaciones($errores){
        $retorno = array();
        $retorno["estado"]="success";
        $retorno['errorValidaciones']=$errores;
        return ReturnJSON::success($retorno);
    }



}