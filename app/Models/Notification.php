<?php

namespace App\Models;

use App\Models\Util\AjaxQuery;
use Exception;
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
        'content_id',
        'isShow',
        'created_at',
        'updated_at',
        'user_id',
        'type',
        'autor_id',
        'autor_table'
    ];

    protected $appends = ['autor','object'];

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getAutorAttribute(){
        try{
            return AjaxQuery::newObject($this->autor_table,$this->autor_id);
        }catch(Exception $e){
            return null;
        }
    }

    public function getObjectAttribute(){
        try{
            return AjaxQuery::newObject($this->content,$this->content_id);
        }catch(Exception $e){
            return null;
        }
    }


    public static function create(array $attributes = [])
    {
        \Log::info('create Not');


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