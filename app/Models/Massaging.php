<?php

namespace App\Models;

use App;
use App\Jobs\ProcessNotifications;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Log;
use stdClass;

/**
 * Class Cliente
 */
class Massaging extends Model
{

    use DispatchesJobs;

    //protected $table = 'messaging';

    public $timestamps = true;

    protected $fillable = [
        'nombre'
    ];


    protected $guarded = [];


    public static function allUsers(){
        if(App::environment('local')){
            return User::where('email','larrealucas20@gmail.com')->/*whereNotNull('token_messaging')->where('token_messaging','!=','')*/get();
        }
        return User::/*whereNotNull('token_messaging')->where('token_messaging','!=','')*/get();
    }

    public static function adminsTeams(){
        if(App::environment('local')){
            User::where('email','larrealucas20@gmail.com')->with('teams')->has('teams')->get();
        }
        return User::/*whereNotNull('token_messaging')->where('token_messaging','!=','')->*/with('teams')->has('teams')->get();
    }
    
    public static function adminsPlayer(){
        if(App::environment('local')){
            return User::where('email','larrealucas20@gmail.com')->/*whereNotNull('token_messaging')->where('token_messaging','!=','')->*/with('player')->has('player')->first();
        }

        return User::/*whereNotNull('token_messaging')->where('token_messaging','!=','')->*/with('player')->has('player')->first();
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
                $ret = self::sendTo($user,$title,$text,$user->token_messaging);
                $data[] = [
                    'user' => $user->completeName,
                    'result' => $ret 
                ];
            }
        }

        return $data;


    }

    public static function sendToUsers($users ,$title,$text,$goTo,$viewEmail = 'default'){
        $data= [];

        foreach($users as $user){
           
            $res = self::sendTo($user,$title,$text,$goTo,$viewEmail);

           $data[] =  [
                'user' => ['id' => $user->id ,'name' => $user->completeName ],
                'result' => $res
            ];
        }
        
        return $data;
    }
    
    public static function sendTo($user ,$title,$text,$goTo,$viewEmail = 'default'){
        
        $message = new Message();
        $message->set($user,$title,$text,$goTo,$viewEmail);

        $job = new ProcessNotifications($message);
        
        //$job->onQueue('default');

        dispatch($job);

    }

  

    public function loadedResults(){
        $users = $this->allUsers();

        $title = 'Ve el resultado de tú Equipo favorito.';
        $msj = 'Ya se encuentran disponible los resultados de todos los partidos de la fecha.';
        $url = 'https://futbol-alem.com/#/home/results';


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

        $fechaSabado = date('D') == 'Sat' ? date('d',strtotime('now')) : date('d',strtotime('next Saturday'));


        $title = '¿Sabes contra quien jugara tu equipo favorito?';
        $msj = 'Ya se encuentra cargado los partidos del Sábado '. $fechaSabado .', ve contra quien lo hará tu equipo.';
        $url = 'https://futbol-alem.com/#/home/games';


        $data = [];
        foreach($users as $user ){
            $res = $this->sendTo($user,$title,$msj,$url,'emails.showGames');

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
        $url = 'https://futbol-alem.com/#/home/publications';


        $data = [];
        foreach($users as $user ){
            $res = $this->sendTo($user,$title,$msj,$url,'emails.newPublication');

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
        $url = 'https://futbol-alem.com/#/go-to??url=https://www.facebook.com/Fútbol-8-Alem-1730791230489387';
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
        
        $url = 'https://futbol-alem.com/#/home/games?create=true';


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
                $data = array_merge($data , $this->setResultGame($game , $game->team_l) );
            }

            if($game->team_v){
                $data = array_merge($data ,  $this->setResultGame($game , $game->team_v) );
            }
        }

        return $data;
    }


    public function setResultGame($game ,$team){


        $title = 'Cargar resultado.';
        $url = 'https://futbol-alem.com/#/results/profile/' . $game->id;

        $users = $team->admins;

        $text  = "Ya puedes ingresar el resultado del partido de " . $team->name;

        return Massaging::sendToUsers($users,$title,$text,$url,'emails.loadResult');
    }
        


    public function test(){
        $user = User::where('role','admin')->first();
        $this->sendTo($user,"test message","probando notificacion movil","facebook.com");
    }

    public function testEmail(){
        $user = User::where('role','admin')->first();
        $user->token_messaging = '';
        $this->sendTo($user,"test message","probando notificacion movil","facebook.com");
    }
}