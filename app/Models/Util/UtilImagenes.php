<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 28/09/18
 * Time: 16:14
 */


namespace App\Models\Util;

use Intervention\Image\ImageManager as Im;
use App\Models\Image;

const IMAGE_HANDLERS = [
    IMAGETYPE_JPEG => [
        'load' => 'imagecreatefromjpeg',
        'save' => 'imagejpeg',
        'quality' => 100
    ],
    IMAGETYPE_PNG => [
        'load' => 'imagecreatefrompng',
        'save' => 'imagepng',
        'quality' => 0
    ],
    IMAGETYPE_GIF => [
        'load' => 'imagecreatefromgif',
        'save' => 'imagegif'
    ]
]; 


class UtilImagenes
{

    public static function saveImageBase64($str_image,$path,$withThumb = false){

        $pathThumb = $path . 'thumb/';

        $pathLocal = public_path($path);
        $pathLocalThumb = public_path($pathThumb);

        if (!is_dir($pathLocal) ){
            //\File::makeDirectory($path,0700,true);
            \Log::info("creando directorio " .$pathLocal);
            
            mkdir($path, 0777,true);
            \Log::info("Directorio creado " .$pathLocal);

        }

        if ($withThumb && !is_dir($pathLocalThumb) ){
            //\File::makeDirectory($path,0700,true);
            \Log::info("creando directorio " .$pathLocalThumb);

            mkdir($pathThumb, 0777,true);
            \Log::info("Directorio creado " .$pathLocalThumb);

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

            if($withThumb){

                $pathThumb =  $pathThumb .$name  ;

                self::createThumbnail($pathName ,$pathThumb ,160);
                \Log::info("Archivo Imagen creada " .$pathThumb);
            }

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


    /**
     * @param $src - a valid file location
     * @param $dest - a valid file target
     * @param $targetWidth - desired output width
     * @param $targetHeight - desired output height or null
     */
    public static function  createThumbnail($src, $dest, $targetWidth, $targetHeight = null) {

        return;
        // 1. Load the image from the given $src
        // - see if the file actually exists
        // - check if it's of a valid image type
        // - load the image resource

        // get the type of the image
        // we need the type to determine the correct loader
        $type = exif_imagetype($src);

        // if no valid type or no handler found -> exit
        if (!$type || !IMAGE_HANDLERS[$type]) {
            return null;
        }

        // load the image with the correct loader
        $image = call_user_func(IMAGE_HANDLERS[$type]['load'], $src);

        // no image found at supplied location -> exit
        if (!$image) {
            return null;
        }


        // 2. Create a thumbnail and resize the loaded $image
        // - get the image dimensions
        // - define the output size appropriately
        // - create a thumbnail based on that size
        // - set alpha transparency for GIFs and PNGs
        // - draw the final thumbnail

        // get original image width and height
        $width = imagesx($image);
        $height = imagesy($image);

        // maintain aspect ratio when no height set
        if ($targetHeight == null) {

            // get width to height ratio
            $ratio = $width / $height;

            // if is portrait
            // use ratio to scale height to fit in square
            if ($width > $height) {
                $targetHeight = floor($targetWidth / $ratio);
            }
            // if is landscape
            // use ratio to scale width to fit in square
            else {
                $targetHeight = $targetWidth;
                $targetWidth = floor($targetWidth * $ratio);
            }
        }

        // create duplicate image based on calculated target size
        $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

        // set transparency options for GIFs and PNGs
        if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {

            // make image transparent
            imagecolortransparent(
                $thumbnail,
                imagecolorallocate($thumbnail, 0, 0, 0)
            );

            // additional settings for PNGs
            if ($type == IMAGETYPE_PNG) {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
            }
        }

        // copy entire source image to duplicate image and resize
        imagecopyresampled(
            $thumbnail,
            $image,
            0, 0, 0, 0,
            $targetWidth, $targetHeight,
            $width, $height
        );

        return call_user_func(
            IMAGE_HANDLERS[$type]['save'],
            $thumbnail,
            $dest,
            IMAGE_HANDLERS[$type]['quality']
        );

    }



}