<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class structures extends Model
{
    protected $table = 'structures';
    protected $fillable = [
        'organisme',
        'description',
        'siege_ville',
        'siege_adresse',
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
    ];
    public function users()
    {
        return $this->hasMany(User::class, 'id_structure');
    }
    public function members_count()
    {
        return $this->hasMany(User::class, 'id_structure')->count();
    }
}
