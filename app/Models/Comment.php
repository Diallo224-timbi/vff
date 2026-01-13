<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //fillable fields
    protected $fillable = [
        'body',
        'user_id',
        'thread_id',
    ];

    //relation avec la table users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //relation avec la table threads
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
    //relation avec la table comment_reactions
    public function reactions()
    {
        return $this->hasMany(CommentReaction::class);
    }
    public function likes():int
    {
        return $this->reactions()->where('type', 'like')->count();
    }
    public function dislikes():int
    {
        return $this->reactions()->where('type', 'dislike')->count();
    }   
}
