<?php

namespace App\Models;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Paise
 */
class Team extends Model
{
    protected $table = 'teams';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'shield_id',
        'cover_page_id'
    ];

    protected $with = ['shield','coverPage','admins'];

    protected $casts = [
        'paid' => 'boolean'
    ];

    protected $guarded = [];

    public function getIsFavoriteAttribute(){
            return $this->isFavorite = ! empty($this->favorite);
    }

    public function favorite(){
        return $this->hasOne(Favorite::class,'table_id','id');
    }

    public function comments(){
        return $this->belongsToMany(Comment::class);
    }

    public function games(){
        return $this->hasMany(Game::class,'v_team')->orWhere('l_team',$this->id);
    }

    public function getNextGameAttribute(){
        $date  = date('Y-m-d');
        //$time = date('H:i:s');
        return $this->games()
                    ->pendientes()
                    ->select('*',DB::raw("CONCAT(date,' ',time) as date_time"))
                    ->whereDate('date','>=',$date)
                    ->orderBy('date_time','asc')
                    ->first();
    }

    public function getOldGameAttribute(){
        $date  = date('Y-m-d');

        return $this->games()
                    ->jugados()
                    ->select('*',DB::raw("CONCAT(date,' ',time) as date_time"))
                    ->whereDate('date','<=',$date)
                    ->orderBy('date_time','desc')
                    ->first();
    }


    public function getStatisticsAttribute(){
        $data = [];

        $data[] = ['name' => 'Ganados', 'value'=> $this->games()->ganados($this->id)->count()];
        $data[] = ['name' => 'Perdidos', 'value'=> $this->games()->perdidos($this->id)->count()];
        $data[] = ['name' => 'Empatados', 'value'=> $this->games()->empatados()->count()];
        $data[] = ['name' => 'Jugados', 'value'=>  $this->games()->jugados()->count()];
        $data[] = ['name' => 'Suspendidos', 'value'=> $this->games()->suspendidos()->count()];

        //nuevo
        return $data;

    }

  

    public static function create(array $attributes = [])
    {

        $model = parent::create($attributes);

        $model->updateAdmins($attributes);

        return $model;
    }

    public function update(array $attributes = [], array $options = [])
    {

        $model =  parent::update($attributes, $options);

        $this->updateAdmins($attributes);

        $this->shield;
        $this->coverPage;

        return $this;
    }
    
    public function delete()
    {
        \Log::info('methods delete team');

        if($this->shield_id){
            \Log::info('with shield id ->delete');
            $this->shield->delete();
        }

        if($this->cover_page_id){
            \Log::info('with cover_page_id id ->delete');

            $this->coverPage->delete();
        }
    }

    //Relations

    public function shield(){
        return $this->belongsTo(Image::class,'shield_id');
    }

    public function coverPage(){
        return $this->belongsTo(Image::class,'cover_page_id');
    }

    public function admins(){
        return $this->belongsToMany(User::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class)->with('photo')->withTimestamps()->wherePivot('current',1);
    }

    //Functions


    public function getAllTeamsSelect(){
        return self::select('id','name')->orderBy('name')->get();
    }

    public function pageProfile(){
        $this->getIsFavoriteAttribute();
        $this->players;
        $this->comments;
        $this->oldGame = $this->oldGame;
        $this->nextGame = $this->nextGame;
        $this['statistics'] = $this->statistics;
        return $this;
    }

    public function pageGames(){

        $status = request()->get('status');
        if($status){
            if($status == 'ganados' || $status == 'perdidos'){  
                $this['games'] = $this->games()->orderBy('date','asc')->jugados()->$status($this->id)->get();
            }
            else if($status == 'suspendidos'){
                $this['games'] = $this->games()->orderBy('date','asc')->$status()->get();
            }
            else{
                $this['games'] = $this->games()->orderBy('date','asc')->jugados()->$status()->get();
            }

        }else{
            $this['games'] = $this->games()->orderBy('date','asc')->get();
        }


        return $this;
    }

    public function removePlayer()
    {
        $player_id = request()->get('player_id');
        $this->players()->detach($player_id);
        # code...
        return 'ok';
    }

    //provate
    private function updateAdmins($attributes){
        $admins = $attributes['admins'];

        if(! empty($admins)){
            $ids = [];
            foreach($admins as $admin){
                $ids[] = $admin['id']; 
                $this->sendNotification($admin['id']);
            }
            $this->admins()->sync($ids);
        }
    }

    public function pageHomeTeams(){
        return $this->orderBy('name','asc')->get();
    }


    public function sendNotification($admin_id)
    {
        $dataPublication = [
            'type' => 'admin_team',
            'user_id' => $admin_id,
            'title' => '',
            'route' => '/team/profile/' . $this->id,
            'content' => 'Team',
            'content_id' => $this->id,
            'autor_table' => 'Team',
            'autor_id' => $this->id
        ];

        Notification::create($dataPublication);
    }

    public function exist(){
        $name = request()->get('name');

        //return $name;

        $result = Team::where('name', $name)->first();

        return $result;

    }

    public function noAdmins(){
        return self::with('admins')->where(function($q){
            $q->whereHas('admins',function($q){
                $q->where('role','!=','admin');
            });
        })
        ->orWhere(function($q){
            $q->doesnthave('admins');
        })->get();
    }

}