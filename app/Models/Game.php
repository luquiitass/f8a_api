<?php

namespace App\Models;

use App\Exceptions\ExepcionValidaciones;
use App\Models\Util\ReturnJSON;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Log;

/**
 * Class Paise
 */
class Game extends Model
{
    protected $table = 'games';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'l_team',
        'v_team',
        'other_team',
        'l_goals',
        'v_goals',
        'status',
        'time',
        'location',
        'description',
        'date',
        'team_creator'
    ];

    public $with = ['team_l','team_v','events'];
    protected $guarded = [];

    public function comments(){
        return $this->belongsToMany(Comment::class);
    }

    public function team_l()
    {
        return $this->belongsTo(Team::class,'l_team','id');
    }

    public function team_v(){
        return $this->belongsTo(Team::class,'v_team','id');
    }

    public function events(){
        return $this->hasMany(Event::class)->with('typeEvent','player','team')->orderBy('time','asc');
    }

    public function scopeJugados($query){
        return $query->where('status','Jugado');
    }

    public function scopePendientes($query){
        return $query->where('status','Pendiente');
    }

    public function scopeSuspendidos($query){
        return $query->where('status','Suspendido');
    }

    public function scopeGanados($query,$team_id){
        $query->where('winner',$team_id)->whereNotNull('winner');
    }

    public function scopePerdidos($query,$team_id){

        $query->where('winner','!=',$team_id)->whereNotNull('winner');
    }

    public function scopeEmpatados($query){


        return $query
                ->jugados()
                ->whereColumn('l_goals','v_goals');
    }


    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);
        $model->setWinner();


        return $model->load('team_v','team_l');
    }

    public function update(array $attributes = [], array $options = [])
    {

        $model =  parent::update($attributes, $options);

       $this->setWinner();

        return $model;
    }
    
    public function delete()
    {
        return parent::delete(); // TODO: Change the autogenerated stub
    }

    public function setWinner(){
        
        if($this->v_goals > $this->l_goals){
            $this->winner = $this->v_team;
            $this->save();
        }else if($this->v_goals < $this->l_goals){
            $this->winner = $this->l_team;
            $this->save();        }
    }


    public function createGameAdmin(){
        $inputs = request()->all();

        if(!empty($inputs['other_team'])){
            $nameNewTeam = $inputs['other_team'];

            if(Team::where('name',$nameNewTeam)->count() > 0){
                throw new Exception('El nombre del equipo ya existe.');
            }
            
            $newTeam = new Team([
                'name' => $nameNewTeam
            ]);

            $newTeam->save();

            $admins_id = User::where('role','admin')->pluck('id')->toArray();

            foreach($admins_id as $admin_id){
                $newTeam->admins()->attach($admins_id);
                $newTeam->sendNotification($admin_id);
            }
            
           
            if($inputs['v_team'] == -1 ){
                $inputs['v_team'] = $newTeam->id;
            }else{
                $inputs['l_team'] = $newTeam->id;
            }
        }

        //Validaciones;

        $game = parent::create($inputs);

        //$game->notificationToAdminsNewGame($inputs['team_creator']);

        /*if(!empty($game->other_team)){
            $game->notificationNewTeamInGame();
        }
        */

        return $game->load('team_l','team_v');
    }

    public function confirm(){
        $this->status = 'Pendiente';
        $this->save();

          
        $team_cha = $this->team_creator == $this->l_team ? $this->team_v : $this->team_l;
        $admins = $this->team_v->admins;

     
        foreach($admins as $admin){
            $dataNotification = [
                'type' => 'challenge-confirm',
                'user_id' => $admin->id,
                'title' => '',
                'route' => '/new-challenge/' . $this->id,
                'content' => 'Team',
                'content_id' => $team_cha->id,
                'autor_table' => 'Team',
                'autor_id' => $this->team_creator
            ];

            Notification::create($dataNotification);
        }


        return $this->load('team_v','team_l');
    }

    public function cancel(){
        $this->status = 'Cancelado';
        $this->save();
        
        
        $team_cha = $this->team_creator == $this->l_team ? $this->team_v : $this->team_l;
        $admins = $this->team_v->admins;


        foreach($admins as $admin){
            $dataNotification = [
                'type' => 'challenge-cancel',
                'user_id' => $admin->id,
                'title' => '',
                'route' => '/new-challenge/' . $this->id,
                'content' => 'Team',
                'content_id' => $team_cha->id,
                'autor_table' => 'Team',
                'autor_id' => $this->team_creator
            ];

            Notification::create($dataNotification);
        }
        
        
        return $this->load('team_v','team_l');



    }
    
    function games()
    {
        return self::query()
                    ->where('status','Pendiente')
                    ->get();
    }

    public function results()
    {
        return self::query()->where('status','Jugado')->get();
    }

    public function gamesByDate(){
        $date = request()->get('date');
        return $this->getGamesByDate($date);
    }

    public function getGamesByDate($date){
        return self::query()
            ->where('date',$date)
            ->withCount(['comments'])
            ->where(function($query)
            {
                return $query->where('status','Pendiente')
                    ->OrWhere('status','Suspendido');
            })
            ->orderBy('time')
            ->get();
    }


    public function pageHomeGames(){
        $now = Carbon::now();
        $dateEnd = Carbon::now()->addMonth(1);
        //return [$now , $dateEnd];
        
        $dates = self::select('date')
                ->where('date', '>=' ,$now->format('Y-m-d'))
                ->where('date', '<=' ,$dateEnd->format('Y-m-d'))
                ->where(function($query)
                {
                    return $query->where('status','Pendiente')
                        ->OrWhere('status','Suspendido');
                })
                ->groupBy('date')
                ->orderBy('date')
                ->get();

        $index = 0;

        $results = [];
        $lastDate = Carbon::now();

        if( count( $dates ) ){
            $lastDate = $dates[$index];
            $results = $this->getGamesByDate($lastDate->date);
            $dates[$index]['results'] = $results;
        }

        $data = [
            'dates' => $dates,
        ];

        return $data;
    }
    
    
    public function adminByDate(){
        $date = request()->get('date');
        return $this->adminGetByDate($date);
    }

    public function adminGetByDate($date){
        return self::query()
            ->where('date',$date)
            ->orderBy('time')
            ->get();
    }


    public function pageAdminAll(){
        $dateInit = Carbon::now()->addMonth(-1);
        $now = Carbon::now();
        $dateEnd = Carbon::now()->addMonth(1);
        //return $dateInit;
        
        $dates = self::select('date')
                ->where('date', '>=' ,$dateInit)
                ->where('date', '<=' ,$dateEnd)
                ->groupBy('date')
                ->orderBy('date')
                ->get();

        
        $data = [
            'dates' => $dates,
        ];

        return $data;
    }
    
    
    //Dates of Results
    public function resultsByDate(){
        $date = request()->get('date');
        return $this->getResultsByDate($date);
    }

    public function getResultsByDate($date){
        return self::query()
            ->where('date',$date)
            ->withCount(['comments'])
            /*->with(['events' => function($q){
                $q->limit(2);
            }])*/
            ->where(function($query)
            {
                return $query->where('status','Jugado')
                    ->OrWhere('status','Suspendido');
            })
            ->orderBy('time')
            ->get();
    }

    public function pageHomeResults(){
        $now = Carbon::now();
        $dateInit = Carbon::now()->addMonth(-1);
        //return $dateInit;
        
        $dates = self::select('date')
                ->where('date', '<=' ,$now->format('Y-m-d'))
                ->where('date', '>=' ,$dateInit->format('Y-m-d'))
                ->where(function($query)
                {
                    return $query->where('status','Jugado')
                        ->OrWhere('status','Suspendido');
                })
                ->groupBy('date')
                ->orderBy('date')
                ->get();

        $index = count($dates) - 1;

        $results = [];
        $lastDate = Carbon::now();

        if($index >= 0 ){
            $lastDate = $dates[$index];
            $results = $this->getResultsByDate($lastDate->date);
            $dates[$index]['results'] = $results;
        }

        
        $data = [
            'dates' => $dates,
        ];

        return $data;
    }


    function dataEvent(){
        $data = [];

        $data['game'] = $this;
        $data['team_l'] = $this->team_l->load('players');
        $data['team_v'] = $this->team_v->load('players');
        $data['events'] = $this->events;

        $data['types_events'] = TypeEvent::all();

        return $data;
    }

    public function dataProfile()
    {
        $this->events;

        return $this;
    }

    public function notificationToAdminsNewGame($team_creator){
        
        $admins = [];
        if(! ($team_creator == $this->v_team)){
            $team_cha = $this->team_v;
            $admins = $this->team_v->admins;
        }else{
            $team_cha = $this->team_l;
            $admins = $this->team_l->admins;
        }

     
        foreach($admins as $admin){
            $dataNotification = [
                'type' => 'new_challenge',
                'user_id' => $admin->id,
                'title' => '',
                'route' => '/new-challenge/' . $this->id,
                'content' => 'Team',
                'content_id' => $team_cha->id,
                'autor_table' => 'Team',
                'autor_id' => $this->team_creator
            ];

            Notification::create($dataNotification);
        }
    }

    public function notificationNewTeamInGame(){


        foreach(User::where('role','admin')->get() as $admin){

            $dataNotification = [
                'type' => 'new_team_in_game',
                'user_id' => $admin->id,
                'title' => $this->other_team,
                'route' => '/games',
                'content' => 'Game',
                'content_id' => $this->id,
                'autor_table' => 'Team',
                'autor_id' => $this->team_creator
            ];

            Notification::create($dataNotification);

        }

    }

   
}