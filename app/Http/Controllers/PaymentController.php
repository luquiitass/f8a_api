<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Log;

class PaymentController extends Controller
{
    //

    public function success()
    {
        Log::debug('payment success' , [ 'params' => json_encode( request()->all() )]);
        # code...
    }


    public function pending()
    {
        Log::debug('payment pending' , [ 'params' => json_encode( request()->all() )]);
        # code...
    }

    public function failure()
    {
        Log::alert('payment failure' , [ 'params' => json_encode( request()->all() )]);
        # code...
    }
}
