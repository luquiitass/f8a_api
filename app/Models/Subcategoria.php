<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class Subcategoria extends Model
{

    protected $table = 'subcategorias';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'categoria_id'
    ];


    protected $guarded = [];


    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);


        return $model;
    }

    public function update(array $attributes = [], array $options = [])
    {

        $model =  parent::update($attributes, $options);

        return $model;
    }
}