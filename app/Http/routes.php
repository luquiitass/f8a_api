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
use Symfony\Component\HttpKernel\DataCollector\AjaxDataCollector;
use Intervention\Image\ImageCacheController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/prueba', function () {
    //echo '<img src="'. url('imagecache/medium/3jfVvZb89Dmx66E.jpeg') .'">';
    //$sub =\App\Models\Team::findOrFail(1);
    dd(public_path(''));
    //DB::table('teams')->where('id', 29)->delete();
});

//Route::get('imagecache2/{template}/{filename}',ImageCacheController::getRouter());


Route::get('/login', function () {
    return 'Api f8a ';
});


Route::group(['prefix'=> '/api/'],function (){

    Route::post('login','Api\AjaxPeticiones@login');
    
    Route::any('methods/User/create',function(){
        $ctr = new AjaxPeticiones();
        return $ctr->methods('User','Create');
    });// Clase = nombre de la clase , funcion = Create ,Update ,Delete

    Route::any('methods/Error/create',function(){
        $ctr = new AjaxPeticiones();
        return $ctr->methods('Error','Create');
    });// Clase = nombre de la clase , funcion = Create ,Update ,Delete

    


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

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

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
