<?php

namespace App\Models;

use Auth;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Cliente
 */
class PassRequest extends Model
{

    protected $table = 'pass_requests';

    public $timestamps = true;

    protected $fillable = [
        'player_id',
        'team_id',
        'status',
        'try'
    ];

    protected $with = ['player','team'];


    protected $guarded = [];


    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);

        $model->sendNotification();

        return $model;
    }

    public function accept(){
        try{
            DB::beginTransaction();
            $this->status = 'Aceptado';
            $this->save();
            $this->player->addToTeam($this->team->id);
            $this->sendNotificationAccept();
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
        return $this;
    }

    public function reject(){
        $this->status = 'Rechazado';
        $this->save();
        $this->sendNotificationReject();
        return $this;
    }


    public function passTeamPage(){
        $team_id = request()->get('team_id');
        $player_id = request()->get('player_id');

        $data = [
            'player' => Player::find($player_id),
            'team' => Team::find($team_id)
        ];

        return $data;
    }

    public function pageTeamRequestToPlayer(){
        $this->load('player','team');
        return $this;
    }

    public function sendNotification(){

        $user = Auth::guard('api')->user();

        //if($user->id != $this->player->user->id){
            $dataPublication = [
                'type' => 'team_request_to_player',
                'user_id' => $this->player->user->id,
                'title' => '',
                'route' => '/team-request-to-player/' . $this->id,
                'content' => 'PassRequest',
                'content_id' => $this->id,
                'autor_table' => 'Team',
                'autor_id' => $this->team->id
            ];

            Notification::create($dataPublication);
        
        //}
    }

    public function sendNotificationAccept(){
        $user = Auth::guard('api')->user();

        //if($user->id != $this->player->user->id){
            foreach($this->team->admins as $admin ){
                $dataPublication = [
                    'type' => 'player_accept_request_of_team',
                    'user_id' => $admin->id,
                    'title' => '',
                    'route' => '/team/profile/' . $this->team->id,
                    'content' => 'PassRequest',
                    'content_id' => $this->id,
                    'autor_table' => 'Player',
                    'autor_id' => $this->player->id
                ];
    
                Notification::create($dataPublication);
            }

           
        
        //}
    }


    public function sendNotificationReject(){
        $user = Auth::guard('api')->user();

        //if($user->id != $this->player->user->id){
            foreach($this->team->admins as $admin ){
                $dataPublication = [
                    'type' => 'player_reject_request_of_team',
                    'user_id' => $admin->id,
                    'title' => '',
                    'route' => '/team/profile/' . $this->team->id,
                    'content' => 'PassRequest',
                    'content_id' => $this->id,
                    'autor_table' => 'Player',
                    'autor_id' => $this->player->id
                ];
    
                Notification::create($dataPublication);
            }

           
        
        //}
    }
        
}