<?php
// app/Models/EventInscription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventInscription extends Model
{
    use HasFactory;

    protected $table = 'event_inscriptions';

    protected $fillable = [
        'event_id',
        'user_id',
        'statut'
    ];

    protected $casts = [
        'statut' => 'string'
    ];

    // Relation avec l'événement
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope pour les inscriptions actives
    public function scopeActives($query)
    {
        return $query->where('statut', 'inscrit');
    }

    // Scope pour les inscriptions annulées
    public function scopeAnnulees($query)
    {
        return $query->where('statut', 'annule');
    }
}