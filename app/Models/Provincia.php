<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Provincia
 */
class Provincia extends Model
{
    protected $table = 'provincias';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'pais_id'
    ];

    protected $guarded = [];


    public function localidades(){

        return $this->hasMany(Localidad::class);

    }
}