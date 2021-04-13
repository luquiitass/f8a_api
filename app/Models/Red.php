<?php

namespace App\Models;

use App\Models\Util\AjaxQuery;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class Red extends Model
{

    protected $table = 'redes';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'url',
        'name_model',
        'model_id'
    ];


    protected $guarded = [];


    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);


        return $model;
    }


    public function pageTeam()
    {
        $inputs = request()->all();
        $nameModel = $inputs['nameModel'];
        $idModel = $inputs['idModel'];

        return self::query()->where('name_model',$nameModel)->where('model_id',$idModel)->get();

    }
    
}