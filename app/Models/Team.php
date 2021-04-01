<?php

namespace App\Models;

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

        return $model;
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
        return $this;
    }


    //provate
    private function updateAdmins($attributes){
        $admins = $attributes['admins'];

        if(! empty($admins)){
            $ids = [];
            foreach($admins as $admin){
                $ids[] = $admin['id']; 
            }
            $this->admins()->sync($ids);
        }
    }

}