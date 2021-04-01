<?php

namespace App\Models;

use App\Models\Util\AjaxQuery;
use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Paise
 */
class Comment extends Model
{
    protected $table = 'comments';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'body',
        'user_id'
    ];

    protected $guarded = [];
    protected $with = ['user'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function add()
    {
        $user = \Auth::guard('api')->user();
        $inputs = request()->all();

        $data = [
            'body' => $inputs['body'],
            'user_id' => $user->id
        ];

        $comment = Comment::create($data);
        $comment->load('user');

        $relaModel = AjaxQuery::newObject($inputs['nameRelationModel'],$inputs['idRelation']);

        $relaModel->comments()->attach($comment->id);

        return $comment;
    }

        
}