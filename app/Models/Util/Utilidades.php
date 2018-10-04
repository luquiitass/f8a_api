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

    public static function isBase64($base64)
    {
        return base64_decode($base64,true) === true;
    }

    public static function isLinkImage($fotoPerfil)
    {
        return strlen($fotoPerfil) < 40;
    }

}