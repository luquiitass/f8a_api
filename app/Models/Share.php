<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class Share extends Model
{

    protected $table = 'shares';

    public $timestamps = true;

    protected $fillable = [
        'nombre'
    ];


    protected $guarded = [];


    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);


        return $model;
    }



    public function createImageGame(){
        $id = request()->get('id');

        $game = Game::find($id);
        return view('/share/result',['game'=>$game]);
    }

    public function shareFacebook(){


        
    }
        
}