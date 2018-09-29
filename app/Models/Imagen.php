<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cliente
 */
class Imagen extends Model
{
    protected $table = 'imagenes';

    public $timestamps = true;

    protected $fillable = [
        'url',
        'nombre',
    ];

    protected $guarded = [];

        
}