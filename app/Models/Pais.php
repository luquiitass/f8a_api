<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Paise
 */
class Pais extends Model
{
    protected $table = 'paises';

    public $timestamps = false;

    protected $fillable = [
        'nombre'
    ];

    protected $guarded = [];

        
}