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
class Clasificado extends Model
{

    public $directorioImagen = "imagenes/clasificados/";

    protected $table = 'clasificados';

    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'trlefono',
        'vencimiento',
        'prioridad',
        'nuevo',
        'permuto',
        'venta',
        'cliente_id',
        'localidad_id',
        'subcategoria_id'
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