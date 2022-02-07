<?php

namespace App\Models;

use App\Jobs\SendReminderEmail;
use App\Models\Util\ReturnJSON;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Log;
use Mail;

/**
 * Class Cliente
 */
class Email extends Model
{

    use DispatchesJobs;

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

     public function notifyCreateGame(){
        $users = User::with('teams')->has('teams')->get();

        foreach($users as $user){
            self::send('emails.createGame',$user,$user,'Créa el partido de la fecha');
        }

        return 'ok';

    }

    /**
     * Funcion para informar a los usuarios administradores de equipos que deben subir los resultados. 
     */
    public function notifyResult(){

    }


    public static function notifyAdmin($message,$subject = ''){
        $admins = User::where('role','admin')->get();

        foreach($admins as $admin){
            parent::sendText($message,$admin,$subject);
        }
    }

    public static function showResults($message='',$subject = ''){
        $users = User::where('role','admin')->get();

        foreach($users as $user){
            parent::send('emails.results',$message,$user,$subject);
        }
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

    public function sendText($text,$user,$subject){

        

        $job = new SendReminderEmail(SendReminderEmail::EmailNotifyAdmin,[
            'text'=>$text,
            'user'=>$user,
            'subject'=> $subject
        ]);

        //$job->delay(60);

        $this->dispatch($job);

        /*$res =  Mail::queue('emails.text',['text'=>$text], function ($m) use ($user,$subject) {
            //$m->from("example@gmail.com", 'Fútbol8 Alem');
            $m->to($user->email, $user->email)->subject($subject);
            //$m->later($when);
        });
*/
        //Log::info('send Email to '. $user->completeName . ' ' . $user->email ,[$res]);

    }

        
}