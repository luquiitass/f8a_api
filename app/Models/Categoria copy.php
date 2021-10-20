<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class Categoria extends Model
{

    protected $table = 'categorias';

    public $timestamps = true;

    protected $fillable = [
        'nombre'
    ];


    protected $guarded = [];


    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);


        return $model;
    }



        
}