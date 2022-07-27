<?php

namespace App\Models;

use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class Category extends Model
{

    protected $table = 'categories';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'description'
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

        return $this;
    }
        
}