<?php

namespace App\Models;

use App\Models\Util\ReturnJSON;
use Illuminate\Database\Eloquent\Model;
use Log;
use Mail;

/**
 * Class Cliente
 */
class Email extends Model
{

    protected $table = 'emails';

    public $timestamps = true;

    protected $fillable = [
        'nombre'
    ];


    protected $guarded = [];


    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);


        return $model;
    }

    /**
     * Notificar a los administradores sobre el grupo de whatsap
     */

     public function notifyGroupWhatsap(){
         $users = User::with('teams')->has('teams')->where('email','larrealucas20@gmail.com')->get();

        
        
         foreach($users as $user){
             self::send('emails.notifyWhatsAap',$user,$user,'Únete al grupo de WhatsApp');
         }

         return 'ok';

     }

    /**
     * Funcion para informar a los usuarios administradores de equipos que deben subir los resultados. 
     */
    public function notifyResult(){

    }




    public function send($view , $data , $user,$subject ){

       $data = ['data' => $data];

       //Log::info('send Email to' ,[$user->completeName . ' ' . $user->email]);
       $viewRender = ['view' => view($view,$data)->render()];
       
        
        $res =  Mail::send($view,$data, function ($m) use ($user,$subject) {
            //$m->from("example@gmail.com", 'Fútbol8 Alem');
            $m->to($user->email, $user->email)->subject($subject);
        });

        Log::info('send Email to '. $user->completeName . ' ' . $user->email ,[$res]);

    }

        
}