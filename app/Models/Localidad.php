<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Localidade
 */
class Localidad extends Model
{
    protected $table = 'localidades';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'provincia_id'
    ];

    protected $guarded = [];

        
}