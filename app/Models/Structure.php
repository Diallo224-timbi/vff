<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Structure extends Model
{
    //
    use HasFactory;
    //$ table defini le nom de la table associee a ce modele
    protected $table = 'structure';
    protected $fillable = [
        'nom_structure',
        'adresse',
        'contact',
        'latitude',
        'longitude',
        'ville',
        'email',
        'code_postal',
    ];
    //relation un a plusieurs entre Structure et User
     public function users()
    {
        return $this->hasMany(User::class, 'id_structure');
    }
   
}
