<?php


namespace App\Helpers;

use App\Http\Requests\LoginRequest;
use App\Models\Util\AjaxQuery;
use App\Models\Util\ReturnJSON;
use Auth;

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

        $collection = AjaxQuery::newModel($clase)->paginate(9);

        return  ReturnJSON::success([$clase =>$collection]);
    }

    public function runFunction($clase,$function)
    {
        //echo('runFunction new mod');
        $ajaxQuery = AjaxQuery::newModel($clase);
        //echo(' run f ');
        $retorno = $ajaxQuery->runFunction($function);
        //echo(' fin run f');
        //dd($retorno);
        //return $retorno;

        return  ReturnJSON::success(["data" => $retorno]);
        
    }

    public function runFunctionModel($clase,$id,$function)
    {
        $retorno = AjaxQuery::newObject($clase,$id)->$function();

        return  ReturnJSON::success(["data" => $retorno]);
        
    }

    public function login(LoginRequest $request) {
        
        $credentials = $request->all();
        
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorizedd'
            ], 401);

        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'User' => $user,
            'api_token' => $user->api_token
        ]);
    }

}