<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Organisme extends Model
{
    // model pour les organismes qui ont plusieurs structures
    protected $table = 'organisme';
    protected $fillable = [
        'nom_organisme',
        'signification',
        'adresse',
        'code_postal',
        'ville',
        'site_web',
    ];
    // relation one to many avec la table structures
    public function structures()
    {
        return $this->hasMany(Structures::class);
    }
}
