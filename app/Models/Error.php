<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class Error extends Model
{

    protected $table = 'errors';

    public $timestamps = true;

    protected $fillable = [
        'status',
        'text',
        'model'
    ];


    protected $guarded = [];


    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);


        return $model;
    }



        
}