<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 30/10/18
 * Time: 17:14
 */

namespace App\Helpers;


use App\Models\Image;
use Barryvdh\Reflection\DocBlock\Type\Collection;
use Psy\Util\Json;

trait TraitImage
{

    public function getFotoPrincipalAttribute($data){
        return $this->fotoPrincipal();
    }

    
    public function fotoPrincipal(){
        return $this->belongsToMany(Image::class)->wherePivot('perfil',1)->first();
    }

    public function Imagees(){
        return $this->belongsToMany(Image::class)->wherePivot('perfil',0);
    }

    public function tr_saveUpdateImagees(){

        \Log::debug('lucas',['entr al update create Imagees']);

        if (request()->has("Imagees")) {


            $Imagees = request()->get('Imagees');

            $exceptId = collect($Imagees)->lists('id')->diff([-1])->toArray();

            \Log::debug('lucas',['antes de llamar deleteImagees']);

            $this->tr_deleteImagees($exceptId);

            \Log::debug('lucas',['despues  de llamar deleteImagees']);

            //$strImagees = request()->get('Imagees');


            //$Imagees =  json_decode($strImagees,true);

            foreach ($Imagees as $img){

                if (array_key_exists('data',$img)){

                    $Image = Image::create($img);

                    if ($Image){
                        \Log::debug('lucas',['Creo y asocio la Image']);


                        $this->Imagees()->attach($Image->id, ['perfil' => 0]);

                    }
                }


            }
            \Log::debug('lucas',['finalizo']);


            //$this->Imagees;
        }
    }


    public function tr_createImage($attributes = null){

        $requstFoto = (($attributes != null) && array_has($attributes,'fotoPrincipal')) ? $attributes['fotoPrincipal'] : request()->get('fotoPrincipal');

        if ($requstFoto){

                //$requstFoto = request()->get("fotoPrincipal");

                $Image = Image::create($requstFoto);

                if ($Image) {
                    $this->Imagees()->attach($Image->id, ['perfil' => 1]);
                    $this->fotoPrincipal();
                }
        }
        else{
            \Log::alert($this->getMorphClass(),['En los imputs No pose foto de perfil']);
        }
    }

    public function tr_updateImage($attributes = null){
        $requstFoto = (($attributes != null) && array_has($attributes,'fotoPrincipal')) ? $attributes['fotoPrincipal'] : request()->get('fotoPrincipal');

        if ($requstFoto){

                //$requstFoto = request()->get("fotoPrincipal");

                if ($this->fotoPrincipal()){
                    $this->fotoPrincipal()->update($requstFoto);
                }else{

                    \Log::info("Lucas ",["Entro creo una Image xq no tenia"]);
                    $Image = Image::create($requstFoto);

                    if ($Image) {
                        $this->Imagees()->attach($Image->id, ['perfil' => 1]);
                    }
                }
        }
    }

    public function tr_deleteImagees($exceptId){

        if (!$exceptId){
            $Imagees = $this->Imagees;
        }else{
            $Imagees = $this->Imagees()->where('Image_id','<>',$exceptId)->get();
        }

        foreach ($Imagees as $Image){
            $Image->delete();
        }

    }

}