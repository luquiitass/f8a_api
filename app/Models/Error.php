<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

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
        'model',
    ];


    protected $guarded = [];


    public static function create(array $attributes = [])
    {
        $model = parent::create($attributes);

        Log::alert('error APP',[
            'url'=> array_has($attributes,'url') ? $attributes['url'] : '' ,
            'line' => array_has($attributes , 'line') ? $attributes['line'] : '',
            'error'=> $model->text]);

        return $model;
    }



        
}