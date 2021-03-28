<?php

namespace App\Models\Util;
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 17/09/18
 * Time: 14:03
 */
class AjaxQuery
{
    private $model;

    private $object;

    private $query;

    public static function newModel($class){
        return new AjaxQuery($class);
    }

    public static function newObject($class,$id){
        $model = new AjaxQuery($class);
        return $model->getObject($id);
    }

    public function __construct($class)
    {
        $class = 'App\Models\\' . $class;

        $this->model = new  $class;
        $this->query = $class::query();
        //dd($this);

        $this->initQuery();

    }

    public function getObject($id){
        $this->object =  $this->model->find($id);
        $this->initConsultObject();
        return $this->object;
    }

    public function initQuery(){

        $métodos_clase = get_class_methods($this);

        foreach ($métodos_clase as $nombre_método) {
            if (str_contains($nombre_método, 'setQuery_')){
                call_user_func(array($this,$nombre_método));
            }
        }
    }

    public function initConsultObject(){
        $métodos_clase = get_class_methods($this);

        foreach ($métodos_clase as $nombre_método) {
            if (str_contains($nombre_método, 'InObject')){
                call_user_func(array($this,$nombre_método));
            }
        }
    }


    //Metodos para insertar query a una consulta de un Modelo

    public function setQuery_With_OfUrl(){
        if (request()->has('with')){
            $with = explode(',',request()->get('with'));
            if (count($with) > 0){
                $this->query->with($with);
            }
        }
    }

    public function setQuery_Where_OfUrl(){
        if (request()->has('whereValue') && request()->has('whereColumn')) {

            $where = request()->get('whereValue');
            $columns = explode(',', request()->get('whereColumn'));

            foreach ($columns as $col) {
                $this->query->where($col, 'LIKE', '%' . $where . '%');
            }
        }
    }


    public function setQuery_Like_OfUrl(){

        if (request()->has('likeValue') && request()->has('likeColumns')) {

            //dd("Entro like , tiene columna y valor");

            $like = request()->get('likeValue');
            $columns = explode(',', request()->get('likeColumns'));

            $this->query->where(function ($que) use ($columns,$like){

                $firstCol = array_shift($columns);

                $que->where($firstCol, 'LIKE', '%' . $like . '%');

                foreach ($columns as $col) {
                    $que->orWhere($col, 'LIKE', '%' . $like . '%');
                }

            });

        }
    }

    public function setQuery_Select_OfUrl(){

        if (request()->has('select') && request()->get('select') != '') {

            $select = explode(',', request()->get('select'));

            //dd($select);

            $this->query->select($select);
        }
    }

    public function setQuery_OrderBy_OfUrl(){

        if (request()->has('orderByColumns') && request()->get('orderByDireccion') != '') {

            $columns = explode(',', request()->get('orderByColumns'));
            $dir = request()->get("orderByDireccion");

            //dd($select);
            foreach ($columns as $col){
                $this->query->orderBy($col,$dir);

            }

        }
    }


    public function setLoadInObject(){
        if (request()->has('load') && request()->get('load')!= ''){
            $loads = explode(',',request()->get('load'));
            $this->object->load($loads);
        }
    }

    public function setSelectInObject(){

        if (request()->has('select') && request()->get('select') != '') {

            $select = explode(',', request()->get('select'));

            $this->query->select($select);
        }
    }


    public function get(){

        return $this->query->get();
    } //Realizar un get de los Query;

    public function paginate($num){
        return $this->query->paginate($num);
    }

    public function runFunction($function){
        return $this->model->$function();
    }

    public function toString(){
        return $this->model->attributes;
    }

}