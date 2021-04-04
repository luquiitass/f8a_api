<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
        'l_goals',
        'v_goals',
        'status',
        'time',
        'location',
        'description',
        'date'
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
        return $this->hasMany(Event::class)->with('typeEvent','player','team');
    }



    public static function create(array $attributes = [])
    {


        $model = parent::create($attributes);


        return $model->load('team_v','team_l');
    }

    public function update(array $attributes = [], array $options = [])
    {

        $model =  parent::update($attributes, $options);

       

        return $model;
    }
    
    public function delete()
    {
        return parent::delete(); // TODO: Change the autogenerated stub
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
       // return [$now , $dateEnd];
        
        $dates = self::select('date')
                ->where('date', '>=' ,$now)
                ->where('date', '<=' ,$dateEnd)
                ->where(function($query)
                {
                    return $query->where('status','Pendiente')
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
            $results = $this->getGamesByDate($lastDate->date);
            $dates[$index]['results'] = $results;
        }

        $data = [
            'dates' => $dates,
        ];

        return $data;
    }
    
    
    
    
    
    
    public function resultsByDate(){
        $date = request()->get('date');
        return $this->getResultsByDate($date);
    }

    public function getResultsByDate($date){
        return self::query()
            ->where('date',$date)
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
                ->where('date', '<=' ,$now)
                ->where('date', '>=' ,$dateInit)
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
}