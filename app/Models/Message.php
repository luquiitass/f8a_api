<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;
use Log;
use stdClass;

/**
 * Class Cliente
 */
class Message //extends Model
{

    protected $table = 'messages';


    protected $user;
    protected $title;
    protected $text;
    protected $goTo;
    protected $viewEmail;

    public $timestamps = true;

    protected $fillable = [
        'nombre'
    ];


    protected $guarded = [];

    public function set($user,$title,$text,$goTo,$viewEmail)
    {
        $this->user = $user;
        $this->title = $title;
        $this->text = $text;
        $this->goTo = $goTo;
        $this->viewEmail = empty( $viewEmail) ? 'default' : $viewEmail;
        
    }



    
    public function sendTo(){
        $result = null;
        if(! empty($this->user->token_messaging)){
            $result  = $this->send($this->title,$this->text, $this->user->token_messaging,$this->goTo);
        }

        if(($result == null || $result->success == 0) && $this->viewEmail != 'noEmail'){
            Log::alert('No tiene token o no ha sido enviada correctamente la notificación');
            return self::sendEmail($this->viewEmail,$this->user,$this->title,$this->text,$this->goTo);
        }

        return $result;
    }

    private function send($title,$text,$token_messaging,$goTo = 'https://futbol-alem.com/#/home/results'){
        $key_app = 'AAAAWNgS9bw:APA91bEsCRBb0Gj4_uKG91K_yDwvMEnkof0nuxKRrnzvfx0SIPTbgHF6fskBRLrSjZ4X34zCebY0Pbo7BfziX1qgDGJ0titxBBILxQfr8VI-iC9mdKXElHWekyoVlNvaUwP9rMK_Fr8h' ; // get API access key from Google/Firebase API's Console

        //$registrationIds = array( 'cyMSGTKBzwU:APA91...xMKgjgN32WfoJY6mI' ); //Replace this with your device token


        // Modify custom payload here
        $notification = array
        (
                'title'     => $title,
                'body'         => $text,
                'icon'=>'https://futbol-alem.com/assets/icon/favicon.png',
                'click_action'=>$goTo
        );

        $data = [
            'info' => ''
        ];

        $fields = array
        (
            'notification'      => $notification,
            'data'              => $data,
            'to'                =>$token_messaging
        );

        $headers = array
        (
            'Authorization: key=' . $key_app,
            'Content-Type: application/json'
        );

        //echo json_encode($fields);
        //echo '<br>';

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); //For firebase, use https://fcm.googleapis.com/fcm/send

        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        return json_decode( $result );

    }

    private function sendEmail($view ,$user,$subject,$text,$goTo){

        $email = new Email();
        $ob = new stdClass();


        if($view == 'default'){
            Log::alert('Enviando email por defecto (text)');

            $ob->success =  $email->sendText($text,$user,$subject);
        }else{
            Log::alert('enviando email personalizado');

            $ob->success =  $email->send($view,["text" => $text, "goTo"=>$goTo],$user,$subject);
        }
        return $ob;
    }

        
}