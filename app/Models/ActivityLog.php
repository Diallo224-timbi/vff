<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends Model
{
    use HasFactory; // pour gerer les test de la base de données

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeFilter($query, $filters) // pour filtrer les logs dans la page d'administration
    {
        if (isset($filters['user_id']) && $filters['user_id'] != '') {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['action']) && $filters['action'] != '') {
            $query->where('action', 'like', '%' . $filters['action'] . '%');
        }
    }
    // Fonction pour créer un log d'activité
    public static function log($action, $description = null)
    {
        $user = auth()->user();
        self::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
    // fonctionb pour créer un log d'activité de creation d'un utilisateur
    public static function logUserCreation(User $user)
    {
        self::log('Création d\'un utilisateur', 'Utilisateur créé: ' . $user->name.' '.$user->prenom.' '.$user->email);
    }
}
