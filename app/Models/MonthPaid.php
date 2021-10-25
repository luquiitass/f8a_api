<?php

namespace App\Models;

use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class MonthPaid extends Model
{

    protected $table = 'months_paid';

    public $timestamps = false;

    protected $fillable = [
        'is_paid_out',
        'paid_out',
        'month',
        'number',
        'balance_sheet_id',
        'amount'

    ];


    protected $guarded = [];

    public function balanceSheet(){
        return $this->belongsTo(BalanceSheet::class);
    }

    public function amontBalance()
    {
        return $this->belongsTo(AmountBalance::class);
    }

    public static function create(array $attributes = [])
    {
        try{
            DB::beginTransaction();
            $model = parent::create($attributes);
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


        
}