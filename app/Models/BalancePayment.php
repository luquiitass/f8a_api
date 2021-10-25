<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class BalancePayment extends Model
{

    protected $table = 'balance_payments';

    public $timestamps = true;

    protected $fillable = [
        'amount',
        'user_id',
        'balance_sheet_id',

    ];


    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function balanceSheet(){
        return $this->belongsTo(BalanceSheet::class);
    }


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