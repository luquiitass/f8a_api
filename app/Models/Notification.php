<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class Notification extends Model
{

    protected $table = 'notifications';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'route',
        'title',
        'content',
        'isShow',
        'created_at',
        'updated_at',
        'user_id',
        'content_id',
        'type'
    ];


    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }


    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);
        $model->user->increaseNotifications();

        return $model;
    }

    public static function createNotification($user_id , $content,$route ,$title = '')
    {
        $data = [
            'user_id' => $user_id,
            'content' => $content,
            'route' => $route ,
            'title' => $title
        ];

        $model =  self::create($data);
        $model->user->increaseNotifications();


        \Log::info("Save Notification",[$model]);
        return $model;
    }

    public function viewed(){
        $this->isShow = true;
        $this->save();
        return $this;
    }


        
}