<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cliente
 */
class Cliente extends Model
{
    protected $table = 'clientes';

    public $timestamps = true;

    protected $fillable = [
        'dni',
        'telefono',
        'nombre',
        'apellido'
    ];

    protected $guarded = [];

        
}