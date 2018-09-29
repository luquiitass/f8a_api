<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 28/09/18
 * Time: 16:14
 */

namespace App\Models\Util;


class UtilImagenes
{
    public static function saveImageBase64($str_image,$path,$name){

        $pathLocal = public_path($path);

        if (!is_dir($pathLocal)){
            \File::makeDirectory($path,0775,true);
            \Log::info("Directorio creado " .$pathLocal);
        }

        $pathName = $path . $name .'.jpg';



        if (!empty($str_image)){
            file_put_contents(public_path($pathName),base64_decode($str_image));
        }

        return $path . $name .'.jpg';
    }

}