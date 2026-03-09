<?php
// app/Models/Event.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'titre',
        'description',
        'type',
        'date_debut',
        'date_fin',
        'lieu',
        'organisateur',
        'nombre_places',
        'cree_par'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'nombre_places' => 'integer'
    ];

    // Relation avec l'utilisateur qui a créé l'événement
    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    // Relation avec les inscriptions
    public function inscriptions()
    {
        return $this->hasMany(EventInscription::class, 'event_id');
    }

    // Relation avec les utilisateurs inscrits (via inscriptions)
    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_inscriptions', 'event_id', 'user_id')
                    ->withPivot('statut', 'created_at')
                    ->withTimestamps();
    }

    // Vérifier si l'événement est complet
    public function getEstCompletAttribute()
    {
        if (!$this->nombre_places) {
            return false;
        }
        return $this->inscriptions()->where('statut', 'inscrit')->count() >= $this->nombre_places;
    }

    // Nombre de places restantes
    public function getPlacesRestantesAttribute()
    {
        if (!$this->nombre_places) {
            return null; // Illimité
        }
        $inscrits = $this->inscriptions()->where('statut', 'inscrit')->count();
        return max(0, $this->nombre_places - $inscrits);
    }

    // Nombre d'inscrits
    public function getNombreInscritsAttribute()
    {
        return $this->inscriptions()->where('statut', 'inscrit')->count();
    }

    // Scope pour les événements à venir
    public function scopeAVenir($query)
    {
        return $query->where('date_debut', '>', now());
    }

    // Scope pour les événements passés
    public function scopePasses($query)
    {
        return $query->where('date_fin', '<', now());
    }

    // Scope pour les événements d'aujourd'hui
    public function scopeAujourdHui($query)
    {
        return $query->whereDate('date_debut', today());
    }

    // Scope pour filtrer par type
    public function scopeDeType($query, $type)
    {
        return $query->where('type', $type);
    }
}