<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;
use MercadoPago;



/**
 * Class Cliente
 */
class Payment extends Model
{

    protected $table = 'payments';

    public $timestamps = true;

    protected $fillable = [
        'status',
        'start',
        'end',
        'amount',
        'preference_id',
        'preference_json',
        'type', 
        'status',
        'team_id',
        'created_at',
        'updated_at'
    ];

    protected $with = ['team'];
    protected $guarded = [];

    

    public function team(){
        return $this->belongsTo(Team::class);
    }
   


    public static function create(array $attributes = [])
    {

        $model = parent::create($attributes);


        return $model;
    }



    public function createPayment(Team $team){

        MercadoPago\SDK::setAccessToken(config('services.mp.private_key_test'));

        // Crea un objeto de preferencia
        $preference = new MercadoPago\Preference();

        // Crea un ítem en la preferencia
        $item = new MercadoPago\Item();
        $item->title = 'Registro de Equipo ' . $team->name;
        $item->quantity = 1;
        $item->unit_price = 500;
        $item->currency_id = "ARS";
        $preference->items = array($item);

        $preference->back_urls = array(
            "success" => "https://api.futbol8alem.com/payment/success",
            "failure" => "https://api.futbol8alem.com/payment/failure",
            "pending" => "https://api.futbol8alem.com/payment/pending"
        );
        $preference->auto_return = "approved";

        $preference->save();
    
        $payment = Payment::create([
            'status' => 'CREATED',
            'amount' => '500',
            'preference_id' => $preference->id,
            'type' => 'START', 
            'team_id' => $team->id
        ]);


        //return $preference->id;
        return $payment;


    }


    public function findPaymentTeam(){

        $team_id = request()->get('team_id');
        $team = Team::find($team_id);
        $user = \Auth::guard('api')->user();


        $payment = self::where('team_id', $team_id)->where('status',"CREATED")->where('preference_id',null)->first();

        if(empty($payment)){
            $payment = $this->createPayment($team);
        }

        $payment->user_id = $user->id;
        $payment->save();
        $payment->user = $user;

        return $payment;
    }
        
}