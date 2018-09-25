<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 23/06/17
 * Time: 02:55
 */

namespace App\Models\Util;


class Utilidades
{
    public static function isApi(){
        return str_contains(request()->url(),'/api/');
    }

}