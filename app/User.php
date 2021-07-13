<?php

namespace App;

use App\Models\Image;
use App\Models\Notification;
use App\Models\Player;
use App\Models\Publication;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Team;
use App\Models\User as ModelsUser;
use Auth;
use DB;
use Illuminate\Foundation\Auth\User as AuthUser;
use Log;
use stdClass;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'email', 'password','api_token','role'
    ];

    protected $with = ['photo'];

    protected $appends = ['completeName'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','api_token'
    ];

    public function teams(){
        return $this->belongsToMany(Team::class);
    }

    public function player(){
        return $this->hasOne(Player::class);
    }

    public function publications(){
        return $this->hasMany(Publication::class)->withCount('comments','likes');
    }

    public function photo()
    {
        return $this->belongsTo(Image::class,'photo_id');
    }

    public function notifications(){
        return $this->hasMany(Notification::class)->orderBy('id','desc');
    }
   
    public function getCompleteNameAttribute(){
        return $this->last_name .' '. $this->first_name;
    }

    public static function create(array $attributes = [])
    {

        $attributes['api_token'] = str_random(50);
        $attributes['password'] = bcrypt($attributes['password']);

        $model = parent::create($attributes);

        $model->savePhoto($attributes);

        $model->load('photo');

        return $model;
    }

    public function update(array $attributes = [], array $options = [])
    {

        $model =  parent::update($attributes, $options);

        $this->savePhoto($attributes);
        $model_ = new stdClass($model);
        $model_->photo = $this->photo;
        $model_->teams = $this->teams;
        $model_->player = $this->player;

        return $model_;
    }


    public function delete()
    {

        if($this->photo_id){
            $this->photo->delete();
        }
    }


    public function savePhoto($attributes)
    {
        if(isset($attributes['photo'])){
            $imageAttribute = $attributes['photo'];

            if( ! empty($attributes['photo_id']) && !empty($imageAttribute['data'])){
                $id = $attributes['photo_id'];
                \Log::info("User savePhoto update");

                $image = Image::find($id);

                $image->update($imageAttribute);
                
            }else if(!empty($imageAttribute['data'])){

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



   public function dataGlobal()
   {
       $user = Auth::guard('api')->user();
       
       //$user->notifications;
       $user->teams;
       $user->player;

       return $user;
   }

   function searchUser(){
       $text = request()->get('text');

       return  User::where('email','like',$text . '%' )
                    ->orWhere('email','like',$text . '%' )
                    ->orWhere('first_name','like',$text . '%' )
                    ->orWhere('last_name','like',$text . '%' )
                    ->select(DB::raw('CONCAT(last_name," ", first_name ," (" ,email ,")" ) AS text ,id'))
                    ->with('player')
                    ->get();
   }

   function pageMyProfile(){

        $user = \Auth::guard('api')->user();
        $user->teams;
        $user->photo;
        $user->player;

       return $user;
   }

   public function profilePage(){
    $this->load('publications');    
    return $this;    
   }


   function pageNotifications(){
       
       return $this->notifications;
   }


   //Funciones del Objeto

   public function increaseNotifications(){
       $this->counts_not += 1;
       \Log::info('increment not 1 in user');
       $this->save();
   }

   public function resetNotifications(){
    $this->counts_not  = 0;
    $this->save();
   }

   


}
