<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class Massaging extends Model
{

    protected $table = 'messaging';

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

    public  function  sendMassaging(){
        $title = request()->get('title');
        $text = request()->get('body');
        $url = request()->get('url');

        $users = User::whereNotNull('token_messaging')->get();

        $data = [];

        foreach($users as $user){
            $ret = $this->send($title,$text,$user->token_messaging);
            $data[] = [
                'user' => $user->completeName,
                'result' => $ret 
            ];
        }

        return $data;


    }


    public function send($title,$text,$token_messaging,$goTo = 'https://futbol8alem.com/#/home/results'){
        define( 'API_ACCESS_KEY', 'AAAAWNgS9bw:APA91bEsCRBb0Gj4_uKG91K_yDwvMEnkof0nuxKRrnzvfx0SIPTbgHF6fskBRLrSjZ4X34zCebY0Pbo7BfziX1qgDGJ0titxBBILxQfr8VI-iC9mdKXElHWekyoVlNvaUwP9rMK_Fr8h' ); // get API access key from Google/Firebase API's Console

        //$registrationIds = array( 'cyMSGTKBzwU:APA91...xMKgjgN32WfoJY6mI' ); //Replace this with your device token


        // Modify custom payload here
        $notification = array
        (
                'title'     => $title,
                'body'         => $text,
                'icon'=>'https://futbol8alem.com/assets/icon/favicon.png',
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
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        echo json_encode($fields);
        echo '<br>';

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); //For firebase, use https://fcm.googleapis.com/fcm/send

        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        return $result;

    }


        
}