<?php

namespace App\Models\Util;

class ReturnJSON
{

    public static function success($arry){
        $retorno = array();
        $retorno["status"]="success";
        $retorno = array_merge($retorno,$arry);
        $json = response()->json($retorno);
        if (Utilidades::isApi()){
            return $json;
        }
        return $json->getData();

    }

    public static function successStore($arry){
        $retorno = array();
        $retorno["status"]="success";
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
        $retorno["status"]="success";
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
        $retorno["status"]="error";
        $retorno["mensaje"]=$error->getMessage();
        return response()->json($retorno,403);
    }


    public static function error($msj){
        $retorno = array();
        $retorno["status"]="success";
        $retorno["msjError"]=$msj;
        $json = response()->json($retorno);
        if (Utilidades::isApi()){
            return $json;
        }
        return $json->getData();

    }

    public static function errorValidaciones($errores){
        $retorno = array();
        $retorno["status"]="error";
        $retorno['errorValidaciones']=$errores;
        return response()->json($retorno,402);
    }



}