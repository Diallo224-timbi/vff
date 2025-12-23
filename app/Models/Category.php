<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //fillable fields
    protected $fillable = [
        'name',
        'description',
    ];

    //relation avec la table threads
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
