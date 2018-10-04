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
class Cliente extends Model
{

    public $directorioImagen = "imagenes/clientes/";

    protected $table = 'clientes';

    public $timestamps = true;

    protected $fillable = [
        'dni',
        'telefono',
        'nombre',
        'apellido',
        'fotoPerfil'
    ];


    protected $guarded = [];


    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);

        $model->fotoPerfil = $model->guardarFotoPerfil($attributes);

        $model->save();


        return $model;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $attributes['fotoPerfil'] = $this->guardarFotoPerfil($attributes);

        $model =  parent::update($attributes, $options);

        return $model;
    }


    public function guardarFotoPerfil($attributes){

        if (!empty($attributes['fotoPerfil'])){

            if (  !Utilidades::isLinkImage($attributes['fotoPerfil']) ){

                $this->eliminarFoto();
                $imagen = $attributes['fotoPerfil'];
                $path = $this->directorioImagen;
                $nombre =str_random(10);
                return UtilImagenes::saveImageBase64($imagen,$path,$nombre);
            }
        }

        return $this->fotoPerfil;

    }

    public function eliminarFoto(){
        if (file_exists( public_path( $this->fotoPerfil) )){
           unlink(public_path($this->fotoPerfil));
        }
    }


    public function imagenes(){
        return $this->belongsToMany(Imagen::class);
    }

        
}