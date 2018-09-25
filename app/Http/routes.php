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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix'=> '/api/'],function (){

	//Route::get('/','Api\AjaxPeticiones@index');

    Route::get('reloadAttribute/{clase}/{id}/{attribute}','Api\AjaxPeticiones@reloadAttribute');

    Route::get('collection/{clase}/','Api\AjaxPeticiones@getCollection');

    Route::get('model/{clase}/{id}','Api\AjaxPeticiones@getObject');

    Route::any('methods/{clase}/{funcion}','Api\AjaxPeticiones@methods');// Clase = nombre de la clase , funcion = Create ,Update ,Delete

    Route::post('paginate/{clase}', 'Api\AjaxPeticiones@paginate');

});
