<?php

namespace App\Models;

use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class AmountTeam extends Model
{

    protected $table = 'amounts_team';

    public $timestamps = false;

    protected $fillable = [
        'price',
        'start',
        'end',
        'team_id',

    ];


    protected $guarded = [];

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public static function create(array $attributes = [])
    {
        $attributes['start'] = Carbon::now();
        $model = parent::create($attributes);       
        $model->team->amount_balance = $model->price ;
        
        $model->team->save(); 

        return $model->load('team');
    }


    public function update(array $attributes = [], array $options = [])
    {
        $model =  parent::update($attributes, $options);

        return $this;
    }


        
}