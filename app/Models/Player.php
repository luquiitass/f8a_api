<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Paise
 */
class Player extends Model
{
    protected $table = 'players';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'name',
        'nick',
        'birth_date',
        'number',
        'height',
        'weight',
        'position_id',
        'photo_id',
        'user_id'
    ];

    protected $with = ['user','photo','position'];


    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function photo(){
        return $this->belongsTo(Image::class);
    }

    public function teams(){
        return $this->belongsToMany(Team::class)->withPivot(['current'])->withTimestamps();
    }

    public function getTeamAttribute(){
         return $this->team = $this->teams()->wherePivot('current',1)->first();
    }

    public function position(){
        return $this->belongsTo(Position::class);
    }

    public function events(){
        return $this->hasMany(Event::class);
    }

    

    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);

        $model->addPlayer($attributes);

        return $model;
    }

    public function update(array $attributes = [], array $options = [])
    {

        $model = parent::update($attributes, $options);

        $this->sendNotification();
        //$this->addPlayer($attributes);

        //$this->position;
        //$this->al = 'algo';

        return $this->profile();
    }
    
    public function delete()
    {
        return parent::delete(); // TODO: Change the autogenerated stub
    }

    public function addPlayer($attributes){
        
        if( ! empty($attributes['team_id']) ){
            $this->teams()->attach($attributes['team_id']);
            $this->sendNotification();
        }
        $this->team;
    }

    public function addToTeam($team_id){
        $team = $this->team;
        if($team){
            $team->pivot->current =  0 ;
            $team->pivot->save();
            $this->sendNotificationChangeTeam($team,$team_id);
        }

        $this->teams()->attach($team_id);
        $this->team;
    }

    public function countGoals(){
        $event = TypeEvent::where('name','Gol')->first();
        return $this->countGoals = $this->events()->where('type_event_id',$event->id)->count();
    }

    public function countRed(){
        $event = TypeEvent::where('name','Roja')->first();
        return $this->countRed = $this->events()->where('type_event_id',$event->id)->count();
    }

    public function countYellow(){
        $event = TypeEvent::where('name','Amarilla')->first();
        return $this->countYellow = $this->events()->where('type_event_id',$event->id)->count();
    }

    public function countAssistance(){
        $event = TypeEvent::where('name','Asistencia')->first();
        return $this->countAssistence = $this->events()->where('type_event_id',$event->id)->count();
    }

    public function profile(){
        $this->countGoals();
        $this->countYellow();
        $this->countAssistance();
        $this->countRed();
        $this->team;
        $data = $this->load('photo','position','events');
        
        return $data;
    }

    public function pageHomePlayers(){
        
        return self::paginate(20);
    }


    public function sendNotification(){

        $user = Auth::guard('api')->user();

        if($this->user_id && $user->id != $this->user_id){

            $dataPublication = [
                'type' => 'create_player',
                'user_id' => $this->user_id,
                'title' => '',
                'route' => '/player/profile/' . $this->id,
                'content' => 'Player',
                'content_id' => $this->id,
                'autor_table' => 'Team',
                'autor_id' => $this->team->id
            ];

            Notification::create($dataPublication);

        }
    } 

    public function sendNotificationChangeTeam($oldTeam,$newTeam_id){

        foreach($oldTeam->admins as $admin){
            $dataPublication = [
                'type' => 'player_change_team',
                'user_id' => $admin->id,
                'title' => '',
                'route' => '/player/profile/' . $this->id,
                'content' => 'Player',
                'content_id' => $this->id,
                'autor_table' => 'Team',
                'autor_id' => $newTeam_id
            ];

            Notification::create($dataPublication);

        }
    }

    public function searchPlayer()
    {
        # code...
        $query = request()->get('query');

        $data = self::where('name', 'LIKE', '%' . $query . '%')
                    ->orWhere('nick', 'LIKE', '%' . $query . '%')
                    ->get();

        return $data;

    }
}