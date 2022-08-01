<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Log;
use MercadoPago;



/**
 * Class Cliente
 */
class Payment extends Model
{
    const PLANS = array(
        1 => [
            "id" => 1,
            "name" => '2 Meses',
            "info" => 'El pago sera por dos meses',
            "countMonths" => 2,
            "amount" => 300,
        ],
        2 => [
            "id" => 2,
            "name" => '6 Meses',
            "info" => 'El pago sera por 6 meses',
            "countMonths" => 6,
            "amount" => 600,
        ],
        3 => [
            "id" => 3,
            "name" => '12 Meses',
            "info" => 'El pago sera por un año',
            "countMonths" => 12,
            "amount" => 1000,
        ]
        );

    protected $table = 'payments';

    public $timestamps = true;

    protected $fillable = [
        'status',
        'start',
        'end',
        'amount',
        'order_id',
        'payment_id',
        'preference_id',
        'preference_json',
        'type', 
        'detail',
        'status',
        'team_id',
        'created_at',
        'updated_at'
    ];

    protected $with = ['team','user'];
    protected $guarded = [];

    

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
   


    public static function create(array $attributes = [])
    {

        $model = parent::create($attributes);


        return $model;
    }



    public function createPayment($user, Team $team, $plan ){

        MercadoPago\SDK::setAccessToken(config('services.mp.private_key_test'));

        // Crea un objeto de preferencia
        $preference = new MercadoPago\Preference();

        $price = $plan['amount'];
        $mounths = $plan['countMonths'] ;
        $plan_name = $plan['name'] ;

        // Crea un ítem en la preferencia
        $item = new MercadoPago\Item();
        $item->title = 'Suscripción de  ' . $team->name . ' por ' . $mounths . ' meses';
        $item->quantity = 1;
        $item->unit_price = $price;
        $item->currency_id = "ARS";
        $preference->items = array($item);

        $preference->back_urls = array(
            "success" => "https://api.futbol-alem.com/payment/success",
            "failure" => "https://api.futbol-alem.com/payment/failure",
            "pending" => "https://api.futbol-alem.com/payment/pending"
        );  


        $preference->auto_return = "approved";

        //$preference->payer->name = $user->completeName;
        //$preference->payer->email = $user->email;

        $preference->notification_url = 'https://api.futbol-alem.com/payment/notification';// Enviar notificación al usuario; 

        $preference->save();

        $dateStart = date('Y-m-d');
        
        $dateEnd = date('Y-m-d', strtotime('+' . $mounths . ' months' ,  strtotime($dateStart) ) );

    
        $payment = Payment::create([
            'status' => 'created',
            'plan_name' => $plan_name,
            'amount' => $price,
            'mounths' => $mounths,
            'preference_id' => $preference->id,
            'type' => 'START', 
            'team_id' => $team->id,
            'detail' => 'Pago por suscripción de un Equipo',
            'start' => $dateStart,
            'end' => $dateEnd,
            'preference_json' => json_encode($preference),
            'user_id' => $user->id
        ]);


        //return $preference->id;
        return $payment;


    }


    public function findPaymentTeam(){

        $team_id = request()->get('team_id');
        $team = Team::find($team_id);
        $user = \Auth::guard('api')->user();
        $paln_id = request('plan_id'); 
        $plan = self::PLANS[$paln_id];

        $payment = self::where('team_id', $team_id)->where(function($query){
                            return $query->where('status','created')
                                        ->orWhere('status','pending');
                        })
                        ->where('preference_id',null)
                        ->first();

                        

        if(empty($payment)){
            $payment = $this->createPayment($user , $team,$plan);
        }

        $payment->user_id = $user->id;
        $payment->save();
        $payment->user = $user;

        $payment->load('team');
        return $payment;
    }

    public static  function success(){

        $inputs = request()->all();

        $payment = self::where('preference_id',$inputs['preference_id'])->first();

        if(! empty($payment)){
            $payment->payment_id = $inputs['payment_id'];
            $payment->order_id = $inputs['merchant_order_id'];
            $payment->status = $inputs['status'];

            $payment->payment_json = json_encode($inputs);

            $date = Carbon::now();
            $date_end = Carbon::now()->addMonth($payment->mounths);

            $payment->start = $date;
            $payment->end = $date_end;
            
            $payment->save();
            $payment->team->paid = 1;
            $payment->team->paid_up_to = $date_end;
            $payment->team->save();

            

            $payment->sendNotificationSuccess();
            dd($payment);
            //Enviar Notificacion , email de pago 
        }


    }


    public static  function pending(){

        $inputs = request()->all();

        //dd($inputs);

        $payment = self::where('preference_id',$inputs['preference_id'])->first();

        if(! empty($payment)){
            $payment->payment_id = $inputs['payment_id'];
            $payment->order_id = $inputs['merchant_order_id'];
            $payment->status = $inputs['status'];

            $payment->payment_json = json_encode($inputs);

            /*$date = new Carbon();
            $date_end = $date->addMonth(1);

            $payment->start = $date;
            $payment->end = $date_end;
            */
            $payment->save();
            //$payment->team->paid = 1;
            $payment->team->save();

            

            $payment->sendNotificationPending();
            dd($payment);
            //Enviar Notificacion , email de pago 
        }


    }

    public static  function failure(){

        $inputs = request()->all();

        //dd($inputs);

        $payment = self::where('preference_id',$inputs['preference_id'])->first();

        if(! empty($payment)){
            $payment->payment_id = $inputs['payment_id'];
            $payment->order_id = $inputs['merchant_order_id'];
            $payment->status = $inputs['status'];

            $payment->payment_json = json_encode($inputs);

            /*$date = new Carbon();
            $date_end = $date->addMonth(1);

            $payment->start = $date;
            $payment->end = $date_end;
            */
            $payment->save();
            //$payment->team->paid = 1;
            $payment->team->save();

            

            $payment->sendNotificationFailure();
            //dd($payment);
            //Enviar Notificacion , email de pago 
        }


    }


    public function pagePaymentsUser()
    {
        $user = \Auth::guard('api')->user();

        return $user->payments;
        # code...
    }





    public function sendNotificationSuccess(){
        $dataPublication = [
            'type' => 'payment_success',
            'user_id' => $this->user_id,
            'title' => '',
            'route' => '/payment/' . $this->id,
            'content' => 'Payment',
            'content_id' => $this->id,
            'autor_table' => 'Team',
            'autor_id' => $this->team_id
        ];

        Notification::create($dataPublication);
    }

    public function sendNotificationPending(){
        $dataPublication = [
            'type' => 'payment_pending',
            'user_id' => $this->user_id,
            'title' => '',
            'route' => '/payment/' . $this->id,
            'content' => 'Payment',
            'content_id' => $this->id,
            'autor_table' => 'Team',
            'autor_id' => $this->team_id
        ];

        Notification::create($dataPublication);
    }

    public function sendNotificationFailure(){
        $dataPublication = [
            'type' => 'payment_failure',
            'user_id' => $this->user_id,
            'title' => '',
            'route' => '/payment/' . $this->id,
            'content' => 'Payment',
            'content_id' => $this->id,
            'autor_table' => 'Team',
            'autor_id' => $this->team_id
        ];

        Notification::create($dataPublication);
    }

    public function api_getPlans(){
        return  self::PLANS ;
    }
        
}