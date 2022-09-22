<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Payment;
use Log;
use MercadoPago\Payer;

class PaymentController extends Controller
{
    //

    public function success()
    {
        Log::debug('payment success' , [ 'params' => json_encode( request()->all() )]);
        # code...

        $payment = Payment::success();

        $id = empty($payment) ? "0" : $payment->id ;

        $url = "https://futbol-alem.com/#/payment/" . $id ;

        header('Location: ' . $url , true);
        exit();
    }


    public function pending()
    {
        Log::debug('payment pending' , [ 'params' => json_encode( request()->all() )]);
        # code...
        $payment = Payment::pending();
        
       
        $id = empty($payment) ? "0" : $payment->id ;

        $url = "https://futbol-alem.com/#/payment/" . $id ;

        header('Location: ' . $url , true);
        exit();
        //die()
    }

    public function failure()
    {
        Log::alert('payment failure' , [ 'params' => json_encode( request()->all() )]);
        
        $payment = Payment::failure();
        
        
        $id = empty($payment) ? "0" : $payment->id ;

        $url = "https://futbol-alem.com/#/payment/" . $id ;

        header('Location: ' . $url , true);
        exit();

        # code...
       
    }

    public function paid(){
        Log::debug('payment paid_out' , [ 'params' => json_encode( request()->all() )]);
    }

    public function notification(){
        Log::alert('payment alert' , [ 'params' => json_encode( request()->all() )]);

    }
}
