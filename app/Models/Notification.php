<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class Notification extends Model
{

    protected $table = 'notifications';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'route',
        'title',
        'content',
        'isShow',
        'created:_at',
        'updated_at',
        'user_id'
    ];


    protected $guarded = [];


    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);


        return $model;
    }



        
}