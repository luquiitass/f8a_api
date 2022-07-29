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

        Payment::success();

        //header("Location: https://futbol-alem.com/#/payment/success");
        //die();
    }


    public function pending()
    {
        Log::debug('payment pending' , [ 'params' => json_encode( request()->all() )]);
        # code...
        Payment::pending();
        //header("Location: https://futbol-alem.com/#/payment/pending");
        //die();
    }

    public function failure()
    {
        Log::alert('payment failure' , [ 'params' => json_encode( request()->all() )]);
        # code...
        Payment::failure();
        //header("Location: https://futbol-alem.com/#/payment/failure");
        //die();
    }

    public function paid(){
        Log::debug('payment paid_out' , [ 'params' => json_encode( request()->all() )]);
    }

    public function notification(){
        Log::alert('payment alert' , [ 'params' => json_encode( request()->all() )]);

    }
}
