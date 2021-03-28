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
    public static function saveImageBase64($str_image,$path){

        $pathLocal = public_path($path);

        if (!is_dir($pathLocal) ){
            //\File::makeDirectory($path,0700,true);
            \Log::info("creando directorio " .$pathLocal);

            mkdir($path, 0777,true);
            \Log::info("Directorio creado " .$pathLocal);

        }

       

        if (!empty($str_image)){
            
            $extension = '.jpg';

            if(strpos($str_image,',') !== false){
                $extension = explode('/', mime_content_type($str_image))[1] ?? 'jpg';

                $data = explode( ',', $str_image );
                $str_image = $data[ 1 ];
            }

            $name = self::generarname($path,$extension);

            $pathName = $path . $name  ;

            file_put_contents(public_path($pathName),base64_decode($str_image));
            chmod(public_path($pathName), 0777);
            \Log::info("Archivo Imagen creada " .$pathName);


        }

        return $name ;// . $name .'.jpg';
    }

    public static function generarname($path,$extension){
        $name = str_random(15) . '.' . $extension;
        if (file_exists($path . $name )){
            return self::generarname($path , $extension);
        }
        return $name;
    }

}