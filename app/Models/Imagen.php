<?php

namespace App\Models;

use App\Exceptions\ExepcionValidaciones;
use App\Helpers\TraitImagen;
use App\Models\Util\ReturnJSON;
use App\Models\Util\Utilidades;
use App\Models\Util\UtilImagenes;
use Exception;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class Imagen extends Model
{

    protected $table = 'imagenes';

    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'url'
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

    public static function createFotoPerfil($path, $request){
        
    }


    public static function generarNombre($path){
        $nombre = str_random(15);
        if (file_exists($path . $nombre)){
            return self::generarNombre($path);
        }
        return $nombre;
    }

        
}