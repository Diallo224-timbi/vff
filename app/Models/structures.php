<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Structures extends Model
{
    protected $table = 'structure';
    
    protected $fillable = [
        'organisme',
        'description',
        'siege_ville',
        'siege_adresse',
        'siege_code_postal',
        'categories',
        'public_cible',
        'zone',
        'type_structure',
        'details',
        'hebergement',
        'ville',
        'code_postal',
        'adresse',
        'site',
        'latitude',
        'longitude',
        'email',
        'telephone',
        'horaires',
        'pays',
        'logo',
        'id_organisme',
    ];
    
    public function users()
    {
        return $this->hasMany(User::class, 'id_structure');
    }
    
    public function members_count()
    {
        return $this->hasMany(User::class, 'id_structure')->count();
    }
    public function organisme()
    {
        return $this->belongsTo(Organisme::class, 'id_organisme');
    }
}