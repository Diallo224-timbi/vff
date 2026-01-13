<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ThreadReaction;
use App\Models\Comment;

use App\Models\User;
use App\Models\Category;

class Thread extends Model
{
    use HasFactory;
    //fillable fields
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'category_id',
    ];
    
    //relation avec la table users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //relation avec la table categories
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    //relation avec la table comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function reactions()
    {
       return $this->hasMany(ThreadReaction::class);
    }
    public function likes():int
    {
        return $this->reactions()->where('type', 'like')->count();
    }
    public function dislikes():int
    {
        return $this->reactions()->where('type', 'dislike')->count();
    }
    public function commentsCount():int
    {
        return $this->comments()->count();
    }      
}
