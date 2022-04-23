<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Http\Controllers\Api\AjaxPeticiones;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FunctionsDeployController;
use App\Models\Game;
use Symfony\Component\HttpKernel\DataCollector\AjaxDataCollector;
use Intervention\Image\ImageCacheController;

Route::get('/', function () {
    return view('welcome');
});

Route::any('/prueba', function () {
    //echo '<img src="'. url('imagecache/medium/3jfVvZb89Dmx66E.jpeg') .'">';
    //$sub =\App\Models\Team::findOrFail(1);
    //return 'hola';
    //DB::table('teams')->where('id', 29)->delete();
    //$fechaSabado = date('D') == 'Sat' ? date('d',strtotime('now')) : date('d',strtotime('next Saturday'));
    /*$data = "null";

    $numPivot = 0;
    $i=0;
    $pre = 0;
    $pivot = 0; 
    $post = 0;
    while($numPivot <= 10){
        $i++;
        $pre = $pre + $i;

        if($pre ==  ($i+2) + ($i+3) ){
            $data = "Primer numero encontrado es " . ($i + 1);
            $numPivot = 20;
        }else if( $pre > $post){
            while($pre < $post){

            }
        }

        if($i > 10){
            $data = 'No funca';
            $numPivot = 20;
        }
    }

    
    */
    
    return App::environment('local') ? 'local' : 'prod';
});



//Route::get('imagecache2/{template}/{filename}',ImageCacheController::getRouter());


Route::get('/login', function () {
    return 'Api f8a ';
});


Route::group(['prefix'=> '/api/'],function (){

    Route::post('login','Api\AjaxPeticiones@login');
    Route::post('loginSocial','Auth\AuthController@loginSocial');
    
    Route::any('methods/User/create',function(){
        $ctr = new AjaxPeticiones();
        return $ctr->methods('User','Create');
    });// Clase = nombre de la clase , funcion = Create ,Update ,Delete

    Route::any('methods/Error/create',function(){
        $ctr = new AjaxPeticiones();
        return $ctr->methods('Error','Create');
    });// Clase = nombre de la clase , funcion = Create ,Update ,Delete


    
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmailApi');
    Route::post('password/reset', 'Auth\PasswordController@resetApi');

    Route::group(['middleware' => ['auth:api']],function (){

        Route::get('test', function () {
            $user = \Auth::guard('api')->user();
            return response()->json($user);
        });


        Route::get('/','Api\AjaxPeticiones@index');

        Route::get('reloadAttribute/{clase}/{id}/{attribute}','Api\AjaxPeticiones@reloadAttribute');

        Route::get('collection/{clase}/','Api\AjaxPeticiones@getCollection');

        Route::get('model/{clase}/{id}','Api\AjaxPeticiones@getObject');

        Route::any('runFunction/{clase}/{function}','Api\AjaxPeticiones@runFunction');

        Route::any('runFunctionModel/{clase}/{id}/{function}','Api\AjaxPeticiones@runFunctionModel');

        Route::any('methods/{clase}/{funcion}','Api\AjaxPeticiones@methods');// Clase = nombre de la clase , funcion = Create ,Update ,Delete

        Route::post('paginate/{clase}', 'Api\AjaxPeticiones@paginate');

        Route::post('image/upload', 'Api\AjaxPeticiones@uploadImage');

    });
});


Route::group(['prefix'=> '/api2/'],function (){

    Route::post('login','Api\AjaxPeticiones@login');


    Route::get('test', function () {
        $user = \Auth::guard('api')->user();
        return response()->json($user);
    });


    Route::get('/','Api\AjaxPeticiones@index');

    Route::get('reloadAttribute/{clase}/{id}/{attribute}','Api\AjaxPeticiones@reloadAttribute');

    Route::get('collection/{clase}/','Api\AjaxPeticiones@getCollection');

    Route::get('model/{clase}/{id}','Api\AjaxPeticiones@getObject');

    Route::any('runFunction/{clase}/{function}','Api\AjaxPeticiones@runFunction');

    Route::any('runFunctionModel/{clase}/{id}/{function}','Api\AjaxPeticiones@runFunctionModel');

    Route::any('methods/{clase}/{funcion}','Api\AjaxPeticiones@methods');// Clase = nombre de la clase , funcion = Create ,Update ,Delete

    Route::post('paginate/{clase}', 'Api\AjaxPeticiones@paginate');

    Route::post('image/upload', 'Api\AjaxPeticiones@uploadImage');

});

Route::auth();
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('/home', 'HomeController@index');


//Rutas de pagos 2
Route::any('/payment/success','PaymentController@success');
Route::any('/payment/pending','PaymentController@pending');
Route::any('/payment/failure','PaymentController@failure');
Route::any('/payment/paid','PaymentController@paid');

Route::get('functionDeploy/setWinner', 'FunctionsDeployController@setWinner');
Route::get('/redirect','ShareController@redirect');

Route::get('shareResult2/{id}', function ($id)
{
    $game = Game::find($id);
    return view('/share/result2',['game'=>$game])->render();
});


Route::get('shareResult/{id}', function ($id)
{
    $game = Game::find($id);
    return view('/share/result2',['game'=>$game])->render();
});

Route::get('shareResultF/{id}', function ($id)
{
    $game = Game::find($id);
    return view('/share/resultF',['game'=>$game])->render();
});
