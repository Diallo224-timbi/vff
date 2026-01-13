<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Thread;

class ThreadReaction extends Model
{
    use HasFactory;
    protected $table = 'thread_reactions';
    protected $fillable = [
        'user_id',
        'thread_id',
        'type', // e.g., 'like' or 'dislike'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }  
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

}
