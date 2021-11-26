<?php

namespace App\Models;

use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class Field extends Model
{

    protected $table = 'fields';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'lat',
        'lng',
        'team_id'
    ];


    protected $guarded = [];

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public static function create(array $attributes = [])
    {
       
        $model = parent::create($attributes);       

        return $model->load('team');
    }


    public function update(array $attributes = [], array $options = [])
    {
        $model =  parent::update($attributes, $options);

        return $this;
    }

    public function pageAllFields(){
        return parent::get();
    }


    public function markers(){
        return $this->where('lat','!=',0)
                    ->where('lng','!=',0)            
                    ->get();
    }
        
}