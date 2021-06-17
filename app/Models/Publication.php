<?php

namespace App\Models;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class Publication extends Model
{

    protected $table = 'publications';

    public $timestamps = true;

    protected $fillable = [
        'text',
        'user_id',
        'image_id'
    ];

    protected $with = ['image','user'];
    protected $appends = ['liked'];


    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function image(){
        return $this->belongsTo(Image::class);
    }

    public function comments(){
        return $this->belongsToMany(Comment::class);
    }

    public function likes(){
        return $this->belongsToMany(User::class,'likes');
    }

    public function getLikedAttribute(){
        $user_id = Auth::guard('api')->user()->id;
        return $this->likes()->wherePivot('user_id',$user_id)->exists();
    }





    public static function create(array $attributes = [])
    {

        $model = parent::create($attributes);
        $model->saveImage($attributes);

        $model->load('image','comments','likes');
        $model->comments_count = $model->comments->count();
        $model->likes_count = $model->likes->count();
        $model->liked;
        return $model;
    }

    public function update(array $attributes = [], array $options = [])
    {

        $model =  parent::update($attributes, $options);

        $this->saveImage($attributes);
        $this->load('image','comments','likes');
        $this->comments_count = $this->comments->count();
        $this->likes_count = $this->likes->count();
        $this->liked;

        return $this;
    }

    public function delete()
    {
        if($this->image_id){
            $this->image->delete();
        }
    }


    public function saveImage($attributes)
    {
        if(isset($attributes['image'])){
            $imageAttribute = $attributes['image'];

            if( ! empty($attributes['image_id']) && !empty($imageAttribute['data'])){
                $id = $attributes['image_id'];
                \Log::info("User saveimage update");

                $image = Image::find($id);

                $image->update($imageAttribute);
                
            }else if(!empty($imageAttribute['data'])){

                \Log::info("User saveimage save");

                $image =  Image::create($imageAttribute);
               
                $this->image_id = $image->id ?? null;
                $this->save();

            }
        }
    }



    public static function all($col = []){
        return parent::select('*')->orderBy('created_at','desc')->get();
    }
    
    public function list(){
        return parent::orderBy('created_at','desc')
            ->withCount(['comments','likes'])
            ->get();
    }

    public function addLike(){
        $user_id = request()->get('user_id');
        $this->likes()->attach($user_id);
        $this->sendNotification('like');
        return $this;
    }

    public function removeLike(){
        $user_id = request()->get('user_id');
        $this->likes()->detach($user_id);
        return $this;
    }

    public function pageShow(){
        $this->load('likes','comments');

        return $this;
    }


    public function sendNotification($typeNotification){
        $user = Auth::guard('api')->user();
        \Log::info("send notification $typeNotification");
        $route = '/publication/' . $this->id ;
        if($this->user_id != $user->id ){

            if($typeNotification == 'like'){
                $route = $route . '/likes';
            }

            $data = [
              'title' => $typeNotification,
              'route' => $route,
              'content_id' => $this->id,
              'content' => 'Publication',
              'type' => $typeNotification,
              'user_id' => $this->user->id,
              'autor_table' => 'User',
              'autor_id' => $user->id
            ];

            $not = Notification::create($data);

        }
    }
        
}