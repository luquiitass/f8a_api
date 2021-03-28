<?php

namespace App\Models;

use App\Models\Util\UtilImagenes;
use Illuminate\Database\Eloquent\Model;
use App\Models;
use DB;
use URL;

/**
 * Class Cliente
 */
class Image extends Model
{

    protected $table = 'images';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'url',
        'thumb',
        'urlExterna'
    ];

    protected $casts = [
        'thumb'=>'boolean',
    ];

    protected $appends = ['urlComplete'];

    protected $guarded = [];

    public function getThumbnailsAttribute(){
        return $this->url . "thumbnails/" . $this->name;
    }

    public function getOriginalAttribute(){
        return $this->url . $this->name;
    }

    public function getUrlCompleteAttribute(){
        return   URL::to($this->url)   . '/' .  $this->name;
    }


    public static function create(array $attributes = [])
    {


        if (array_has($attributes,'data') && strlen($attributes["data"]) > 10 ){

            $attributes = self::createFoto($attributes);


            $model = parent::create($attributes);

            //self::saveRelation($attributes['saveIn'],$model);

            return $model;
        }

        //throw new \Exception(collect($attributes)->toJson());

        if(array_has($attributes,"urlExterna") && strlen($attributes['urlExterna']) > 0){


            $model = parent::create($attributes);

            return $model;
        }


        return null;
    }

    public function update(array $attributes = [], array $options = []){

        \Log::info("Update image");

        if (array_key_exists('data',$attributes)){

            if (strlen($attributes["data"]) > 10 ) {

                $attributes = $this->updateFoto($attributes);

                $this->deleteFile();

                parent::update($attributes, $options);
            }

        }

        return $this;
    }

    public function delete()
    {
        \Log::info('methods delete IMAGE');

        $this->deleteFile();

        //Image::destroy($this->id);
        DB::table($this['table'])->where('id', $this->id)->delete();

    }


    public static function createFoto($attributes){

        $data = $attributes["data"];
        $url = $attributes["url"];


        $withThumb = $attributes['thumb'];
        $attributes["name"] = UtilImagenes::saveImageBase64($data,$url,$withThumb);

        return $attributes;
    }

    public function updateFoto($attributes){

        $data = $attributes["data"];

        $url = $this->url;

        $withThumb = $attributes['thumb'];

        $attributes["name"] = UtilImagenes::saveImageBase64($data,$url,$withThumb);

        return $attributes;
    }


   

    private function deleteFile()
    {
        if ( !is_dir(public_path($this->getOriginalAttribute())) && \File::exists(public_path($this->getOriginalAttribute()))){
            unlink( public_path($this->getOriginalAttribute()));
            \Log::info('methods deleteFile IMAGE UNLINK');


        }else{
            
           \Log::info('delete file not found  imagen ' . $this->getOriginalAttribute());

        }

        if ( !is_dir(public_path($this->getThumbnailsAttribute())) && \File::exists(public_path($this->getThumbnailsAttribute()))){
            unlink(public_path($this->getThumbnailsAttribute()));

            \Log::info('delete file imagen ' . $this->getThumbnailsAttribute());

        }else{
           \Log::info('delete file not found  imagen ' . $this->getThumbnailsAttribute());

        }
    }

    public function copiarFotoGoogle(){

    }


    public static function saveRelation($data,$image){
        $model = $data['model'];
        $attribute_name = $data['attribute_name'];
        $model_id = $data['model_id'];
        $many = $data['many'];

        if(! $many ) {
            $object = $model::find($model_id);

            $object->$attribute_name = $image->id;

            $object->update();

        }else{

        }
    }

}