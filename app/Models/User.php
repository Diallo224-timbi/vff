<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'prenom',
        'email',
        'password',
        'phone',
        'etatV',
        'role',
        'adresse',
        'ville',
        'code_postal',
        'created_at',
        'id_structure',
        'chart'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getEtatVAttribute($value)
    {
        return strtolower($value);
    }

    /**
     * Vérifie si l'utilisateur est validé
     *
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->etatV === 'valider';
    }
    //relation avec la table structure
    public function structure()
    {
        return $this->belongsTo(Structures::class, 'id_structure');
    }
    //relation avec la table threads
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
    //relation avec la table comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    } 
}
