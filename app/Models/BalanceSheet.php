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
class BalanceSheet extends Model
{

    protected $table = 'balance_sheets';

    public $timestamps = true;

    protected $fillable = [
        'last_month_payment_out',
        'balance',
        'balance_start',
        'balance_old',
        'start_date',
        'end_date',
        'team_id',
        'user_id',
        'active'
    ];

    protected $with=['monthsPaid','balancePayments'];
    
    protected $appends = ['total'];


    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function monthsPaid(){
        return $this->hasMany(MonthPaid::class);
    }

    public function unpaidMonths(){
        return $this->monthsPaid()->where('is_paid_out', 0);
    }

    public function balancePayments()
    {
        return $this->hasMany(BalancePayment::class);
    }

    public function getTotalAttribute(){
        return $this->balance_old + $this->balance;
    }


    public static function create(array $attributes = [])
    {

        try{
            DB::beginTransaction();
            
            $attributes['balance_old'] = $attributes['balance_start']  = empty($attributes['balance_start']) ? 0 : intval( $attributes['balance_start'] );
            
            if($attributes['balance_old'] > 0 ){
                $attributes['balance'] = $attributes['balance_old'] ;
                $attributes['balance_old'] = 0;
            }
            
            $attributes['start_date'] = Carbon::now();

            $model = parent::create($attributes);

            $monthPaid = $model->createNextMonthPaid();

            //$cash = $model->balance_old;
            //$model->processPaidMounth($monthPaid, $cash );
          

            $model->load('team','user','monthsPaid');

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

        $this->monthsPaid()->delete();
        parent::delete();
    }


    public function updateCalculatedData(){

    }


    /**
     * Registra el pago de un Monto N, procesas los meses que son pagados con el importe ingresado
     */
    public function addPayment()
    {
        $inputs = request()->only('amount');
        $inputs['balance_sheet_id'] = $this->id;
        $inputs['user_id'] = $this->user->id;

        $balancePayment = BalancePayment::create($inputs);
        $cash = $balancePayment->amount;

        Log::info('realiza el pago de $' . $cash);

        // si tiene deudas pendientes 
        if($this->balance_old < 0 ){
            $this->balance_old = $this->balance_old + $cash;

            if($this->balance_old < 0 ){
                Log::debug('decuenta balance_old, balance_old aun negativo :' .$this->balance_old);
                $this->save();
                return $this->load('balancePayments');
            }else{
                Log::debug('cancela balace_old y sobra:' . $this->balance_old);
                //$this->balance = $this->balance +  $this->balance_old;
                $cash = $this->balance_old;
                $this->balance_old = 0;
                $this->save();
                //$this->processPaidMounth($balancePayment,$cash);
                
            }
        }

        foreach($this->unpaidMonths as $monthPaid){
            Log::debug('obtiene mes inpago, balance:' . $this->balance);            
            $this->processPaidMounth($monthPaid,$cash);

            if($cash <= 0 )
                break;
        }

        if($cash > 0 ){
            Log::info('balance antes de añadir o sumar cash-> balance:' . $this->balance . ' cash:' . $cash  );
            if($this->balance < 0){
                $this->balance = $cash;
                Log::info('balance negativo, se iguala a cash. balance:' . $this->balance . ' cash:' . $cash  );
            }else{
                $this->balance += $cash;
                Log::info('balance positivo, se suma cash. balance:' . $this->balance . ' cash:' . $cash  );
            }

            $this->save();
        }

        /*
        while($cash > 0){
            Log::debug('pagando meses futuros');

            $monthPaid = $this->createNextMonthPaid();

            $this->processPaidMounth($monthPaid,$cash);
        }
        */
        
        Log::info('Finaliza el proceso con un balace de : ' . $this->balance);
        return $this->load('balancePayments');

    }

    /**
     * Crea un mes de pago mayor al ultimo existente.
     */
    public function createNextMonthPaid(){
        Log::debug('creando mes');
        //Obtiene el ultimo mes creado
        $lastMonthPayment = $this->monthsPaid()->orderBy('month','desc')->first();
        
        //Si no se encuentra algun mes en los registros, se creara uno del mes actual;
        $nextMonth = Carbon::now();

        if($lastMonthPayment){
            $lastMonth= new Carbon($lastMonthPayment->month);//->addMonth(1);;
            $nextMonth = $lastMonth->addMonth(1);
        }
        Log::debug('balance antes de crear el mes:' . $this->balance);

        $is_paid_out = 0;
        $paid_out = 0;
  //      $last_month_payment_out = null;

        $futureBalance =  $this->balance - $this->team->amount_balance ;

        if($futureBalance >= 0 ){
            $is_paid_out = 1;
            $paid_out = $this->team->amount_balance;
//            $last_month_payment_out = $nextMonth->format('Y-m-d');
            $this->last_month_payment_out = $nextMonth->format('Y-m-d');

        }
        else if ( $futureBalance > ( - $this->team->amount_balance )){
            $paid_out = $this->team->amount_balance + $futureBalance;
        }


    
        $monthPaid =  MonthPaid::create([
            'number' => $this->monthsPaid->count() + 1,
            'amount' => $this->team->amount_balance,
            'balance_sheet_id' => $this->id,
            'month' => $nextMonth->format('Y-m-d'),
            'paid_out' => $paid_out,
            'is_paid_out' => $is_paid_out,    
        ]);

        $this->balanceSub( $monthPaid->amount ); 

        $this->save();

        Log::debug('balance despues de crear un nuevo mes:' . $this->balance);

        return $monthPaid;
    }

    /**
     * Realiza el pago total o parcial de un mes
     * @param MonthPaid $monthPaid 
     *  Mes que debe realizar el pago
     */
    public function processPaidMounth($monthPaid,&$cash)
    {   
        Log::debug(' procesa mes a pagar id:'. $monthPaid->id. ', cash : ' . $cash);
        if($cash <= 0){
            Log::debug('No paga, saldo negativo');
            return ;
        }

        if($monthPaid->is_paid_out)
            return;

        $difference = ($monthPaid->amount - $monthPaid->paid_out) ;

        $cash =  $cash - $difference ;
        Log::debug('saldo depues de pagar el mes de ' . $monthPaid->amount . ' -> saldo actual : ' .$cash );

        if($cash >= 0 ){
            $this->balanceAdd($difference) ; // balance += $difference;
            $monthPaid->paid_out = $monthPaid->amount;
            $monthPaid->is_paid_out = 1;
            $this->last_month_payment_out = $monthPaid->month;
            $monthPaid->save();
            Log::debug('mes pagado por completo');
        }else {
            $monthPaid->paid_out = $monthPaid->amount + $cash;
            $monthPaid->save();
            Log::debug('se ha pagado ' . $monthPaid->paid_out . ' del total:' . $monthPaid->amount);
            if($this->balance < 0){
                $this->balanceAdd($monthPaid->paid_out) ; // balance += $monthPaid->paid_out;
                Log::info('Se ha sumado al balance el dinero que restaba del pago');
            }else{
                Log::info('balance positivo, no se ha sumado el cash sobrante.');
            }
        }

        Log::info('balance : ' . $this->balance . '  paid_out: ' . $monthPaid->paid_out ) ;

        //$this->balance += $monthPaid->paid_out;

        //Log::info('balance despues del pago : ' . $this->balance  ) ;

        //$this->balance += $cash;

        //Log::info('balance despues de sumar el resto : ' . $this->balance . '  cash: ' . $cash ) ;

        $this->save();
    }


    function balanceAdd($b) { 
        return $this->balance += $b; 
    }
    
    function balanceSub($b) { 
        return $this->balance -= $b; 
    }


    //peticiones de vistas

    public function pageList(){
        $team_id  = request('team_id');
        return parent::where('team_id',$team_id)->get();
    }


    public function pageMonths(){
        return $this->load('monthsPaid','user','unpaidMonths');
    }
        
}