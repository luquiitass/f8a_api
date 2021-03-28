<?php

namespace App\Models;

use App\Http\Requests\LoginRequest;
use App\Models\Util\ReturnJSON;
use App\User as AppUser;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use Response;

/**
 * Class Cliente
 */
class User extends AppUser
{

    protected $table = 'users';

    protected $with = ['photo'];

    public function photo()
    {
        return $this->belongsTo(Image::class,'photo_id');
    }

    public static function create(array $attributes = [])
    {

        $attributes['api_token'] = str_random(50);
        $attributes['password'] = bcrypt($attributes['password']);

        $model = parent::create($attributes);

        $model->savePhoto($attributes);

        return $model;
    }

    public function update(array $attributes = [], array $options = [])
    {

        $model =  parent::update($attributes, $options);

        $this->savePhoto($attributes);

        return $model;
    }


    public function delete()
    {
        \Log::info('methods delete team');

        if($this->photo_id){
            $this->photo->delete();
        }
    }


    public function savePhoto($attributes)
    {
        if(isset($attributes['photo'])){
            $imageAttribute = $attributes['photo'];

            if( ! empty($imageAttribute['id'])){
                $id = $imageAttribute['id'];
                \Log::info("User savePhoto update");

                $image = Image::find($id);

                $image->update($imageAttribute);

            }else{

                \Log::info("User savePhoto save");

                $image =  Image::create($imageAttribute);
               
                $this->photo_id = $image->id ?? null;
                $this->save();

            }
        }
    }

   public function home()
   {
       $user = Auth::guard('api')->user();

       return [$user,'Otro dato'];
   }


   function searchUser(){
       $text = request()->get('text');

       return  User::where('email','like',$text . '%' )
                    ->orWhere('email','like',$text . '%' )
                    ->orWhere('first_name','like',$text . '%' )
                    ->orWhere('last_name','like',$text . '%' )
                    ->select(DB::raw('CONCAT(last_name," ", first_name ," (" ,email ,")" ) AS text ,id'))
                    ->get();
   }


}