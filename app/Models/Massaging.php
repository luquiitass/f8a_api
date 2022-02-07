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


    public function allUsers(){
        return User::whereNotNull('token_messaging')->where('token_messaging','!=','')->get();
    }

    public function adminsTeams(){
        return User::whereNotNull('token_messaging')->where('token_messaging','!=','')->with('teams')->has('teams')->get();
    }
    
    public function adminsPlayer(){
        return User::whereNotNull('token_messaging')->where('token_messaging','!=','')->with('player')->has('player')->first();
    }

    public function findUser(){
        $id = request()->get('user_id');

        return User::findOrFail($id);
    }


    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);


        return $model;
    }


    public  function  sendMassaging(){
        $title = request()->get('title');
        $text = request()->get('body');
        $url = request()->get('url');

        $users = User::get();

        $data = [];

        foreach($users as $user){
            if(! empty($user->token_messaging)){
                $ret = $this->send($title,$text,$user->token_messaging);
                $data[] = [
                    'user' => $user->completeName,
                    'result' => $ret 
                ];
            }
        }

        return $data;


    }

    public static function sendToUsers($users ,$title,$text,$goTo){
        $data= [];

        foreach($users as $user){
           
            $res = self::sendTo($user,$title,$text,$goTo);

           $data[] =  [
                'user' => ['id' => $user->id ,'name' => $user->completeName ],
                'result' => $res
            ];
        }
        
        return $data;
    }
    
    public static function sendTo($user ,$title,$text,$goTo){
        if(! empty($user->token_messaging)){
            return parent::send($title,$text, $user->token_messaging,$goTo);
        }
    }

    public function send($title,$text,$token_messaging,$goTo = 'https://futbol8alem.com/#/home/results'){
        $key_app = 'AAAAWNgS9bw:APA91bEsCRBb0Gj4_uKG91K_yDwvMEnkof0nuxKRrnzvfx0SIPTbgHF6fskBRLrSjZ4X34zCebY0Pbo7BfziX1qgDGJ0titxBBILxQfr8VI-iC9mdKXElHWekyoVlNvaUwP9rMK_Fr8h' ; // get API access key from Google/Firebase API's Console

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

    public function loadedResults(){
        $users = $this->allUsers();

        $title = 'Ve el resultado de tú Equipo favorito.';
        $msj = 'Ya se encuentran disponible los resultados de todos los partidos de la fecha.';
        $url = 'https://futbol8alem.com/#/home/results';


        $data = [];
        foreach($users as $user ){
            $res = $this->sendTo($user,$title,$msj,$url);

            $data[] = [
                'user' => ['id' => $user->id ,'name' => $user->completeName ],
                'result' => $res
            ];
        }

        //Email::showResults('','Resultados cargados');

        return $data;
    }

    public function showGamesLoaded(){
        $users = $this->allUsers();

        $title = '¿Sabes contra quien jugara tu equipo favorito?';
        $msj = 'Ya se encuentra cargado los partidos del Sábado 9, ve contra quien lo hará tu equipo.';
        $url = 'https://futbol8alem.com/#/home/games';


        $data = [];
        foreach($users as $user ){
            $res = $this->sendTo($user,$title,$msj,$url);

            $data[] = [
                'user' => ['id' => $user->id ,'name' => $user->completeName ],
                'result' => $res
            ];
        }


        return $data;
    }

    public function after(){
        $users = $this->allUsers();

        $title = 'Tercer tiempo';
        $msj = 'Estas en el el 3° Tiempo??? compártelo con amigos.';
        $url = 'https://futbol8alem.com/#/home/publications';


        $data = [];
        foreach($users as $user ){
            $res = $this->sendTo($user,$title,$msj,$url);

            $data[] = [
                'user' => ['id' => $user->id ,'name' => $user->completeName ],
                'result' => $res
            ];
        }

        return $data;
    }

    public function pageFacebook(){
        $users = $this->allUsers();

        $title = 'Pagina de Facebook';
        $msj = 'Te sugerimos darle Me gusta a nuestra pagina de facebook "Fútbol 8 Alem" donde podrás obtener y compartir datos de la pagina web.';// En la pagina de facebook podrás ver y compartir los partidos, tutoriales, y otros datos de la pagina web.';
        $url = 'https://futbol8alem.com/#/go-to??url=https://www.facebook.com/Fútbol-8-Alem-1730791230489387';
        //$url = 'http://192.168.1.15:4200/#/go-to??url=https://www.facebook.com/Fútbol-8-Alem-1730791230489387';

 
        $data = [];
        foreach($users as $user ){
            $res = $this->sendTo($user,$title,$msj,$url);

            $data[] = [
                'user' => ['id' => $user->id ,'name' => $user->completeName ],
                'result' => $res
            ];
        }

        return $data;
    }

    public function adminCreateGame(){

        $users = $this->adminsTeams();

        $title = 'Crea el partido';
        
        $url = 'https://futbol8alem.com/#/home/games?create=true';


        $data = [];
        foreach($users as $user ){

            $msj = 'Eres administrador de un equipo, no te olvides de registrar el partido de la próxima fecha para que los seguidores sepan contra quien juegan';
            
            $res = $this->sendTo($user,$title,$msj,$url);

            $data[] = [
                'user' => ['id' => $user->id ,'name' => $user->completeName ],
                'result' => $res
            ];
        }

        return $data;
    }

    
    public function addResultGames(){
        $games = Game::todayPendingGames(); 
        $data = [];

        foreach($games as $game){
            $data = [];
            if($game->team_l){
                $data = array_merge($data , $this->setResultGame($game->team_l) );
            }

            if($game->team_v){
                $data = array_merge($data ,  $this->setResultGame($game->team_v) );
            }
        }

        return $data;
    }


    public function setResultGame($team){


        $title = 'Cargar resultado.';
        $url = 'https://futbol8alem.com/#/results/profile/' . $this->id;

        $users = $team->admins;

        $text  = "Ya puedes ingresar el resultado del partido de " . $team->name;

        return Massaging::sendToUsers($users,$title,$text,$url);
    }
        
}