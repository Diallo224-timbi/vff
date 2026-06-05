<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //fillable fields
    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];

    //relation avec la table threads
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
    //relation avec la table users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
