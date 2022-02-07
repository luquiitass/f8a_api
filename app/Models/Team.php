<?php

namespace App\Models;

use Carbon\Carbon;
use DB;
use Exception;
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
        'cover_page_id',
        'amount_balance'
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

    public function redes()
    {
        return $this->hasMany(Red::class,'model_id','id')->where('name_model','Team');
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

        if($this->players->count() > 0){
            throw new Exception('El equipo tiene jugadores aociados');
        }

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

    public function field(){
        return $this->hasOne(Field::class);
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

    public function favorites(){
        return $this->belongsToMany(User::class,'favorites','table_id')->withPivot(['table_name'])->wherePivot('table_name','Team');
    }

    public function amontsBalance(){
        return $this->hasMany(AmountBalance::class);
    }

    public function balanceSheets(){
        return $this->hasMany(BalanceSheet::class)->with('user');
    }

    public function balancePayments(){
        
        return $this->hasManyThrough(BalancePayment::class,BalanceSheet::class)->with('balanceSheet')->with('user');

    }


    public function expenses(){
        return $this->hasMany(Expense::class)->where('type','expense');//->orderBy('date','asc');
    }

    public function entrys(){
        return $this->hasMany(Expense::class)->where('type','entry');//->orderBy('date','asc');
    }

    //Functions

    public function allTeams(){
        return parent::orderBy('name')->get();
    }

    public function getAllTeamsSelect(){
        return self::select('id','name')->orderBy('name')->with('field')->get();
    }

    public function pageProfile(){
        $this->getIsFavoriteAttribute();
        $this->players;
        $this->comments;
        $this->field;
        $this->redes;
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

    public function pageBalanceSheet()
    {
        //dd( $this->balanceSheets );
        //$this->balance_sheets = $this->balanceSheets->sortBy('user.first_name');
        return $this->load('balanceSheets');
    }

    public function pageExpenses(){
        return $this->load('expenses.user');
    }

    public function pageEntrys(){
        return $this->load('entrys.user');
    }

    public function pageAccounting(){
        $this->totalBalancePayments = $this->balancePayments->sum('amount');
        $this->totalExpense = $this->expenses->sum('amount');
        $this->totalEntry = $this->entrys->sum('amount');
        $this->totlaPendingFees= $this->balanceSheets()
                                        ->select(DB::raw(' * ,balance_old + balance as total'))
                                        //->where('total','<',0)
                                        ->get()
                                        ->filter(function($item, $key){
                                            return $item->total < 0;
                                        })
                                        ->sum('total');

        $this->cash = ($this->totalBalancePayments + $this->totalEntry )  - $this->totalExpense;

        return $this;
    }

    public function pagePayments(){
        return $this->load('balancePayments');
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


     /**
     * Añadir un mes a todos los integrantes de un equipo
     */

    public function addMonthPaidToAll(){
        foreach( $this->balanceSheets as  $balanceSheet ){
            $balanceSheet->createNextMonthPaid();
        }
    }

    public function addField(){
        $id = request('field_id');

        $field = Field::find($id);

        if( !empty($field->team_id) && $field->team_id != $this->id )
            throw new Exception('Esta cancha ya pertenece a un equipo');

        if(! empty($this->field)){
            $this->field->team_id = NULL;
            $this->field->save();
        }


        $field->team_id = $this->id;
        $field->save();

        $this->save();

        return $field;
    }

    public function removeField(){
        if($this->field){
            $this->field->delete();
        }
    }

}