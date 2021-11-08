<?php

namespace App\Models;

use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Log;

/**
 * Class Cliente
 */
class Expense extends Model
{

    protected $table = 'expenses';

    public $timestamps = true;

    protected $fillable = [
        'amount',
        'description',
        'date',
        'team_id',
        'user_id',
        'type',
    ];

    //protected $with=['user','team'];


    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }

   
    public static function create(array $attributes = [])
    {

        try{
            DB::beginTransaction();

            $attributes['type'] = 'expense';
            
            $model = parent::create($attributes);

            $model->load('team','user');

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            throw $e;
        }

        return $model;
    }


    public function update(array $attributes = [], array $options = [])
    {
        $model =  parent::update($attributes, $options);

        return $this;
    }

    public function delete()
    {

        parent::delete();
    }


 
        
}