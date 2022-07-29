<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;

use App\Http\Requests;

class ShareController extends Controller
{
    //

    public function redirect(){

        $env = App::environment();
        //dd($env);

        $url_base = $env == 'local' ? 'http://192.168.1.15:4200/' : "https://futbol-alem.com/" ;

        $url = request()->get('url');

        //dd($url);

        header("Location: $url_base#". $url );
        die();

    }
}
