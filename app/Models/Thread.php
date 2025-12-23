<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
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
}
