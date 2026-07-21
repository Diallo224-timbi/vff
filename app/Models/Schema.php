<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Schema extends Model
{
    use HasFactory;

    protected $table = 'schemas';

    protected $fillable = [
        'title',
        'description',
        'category',
        'sub_category',
        'data',
        'file_path',
        'file_name',
        'user_id',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accesseurs
    |--------------------------------------------------------------------------
    */

    public function getFileUrlAttribute()
    {
        return $this->file_path
            ? Storage::url($this->file_path)
            : null;
    }

    public function getCategoryLabelAttribute()
    {
        $labels = [
            'GT1' => 'GT1 - Réseau VIF-VC & coordination entre acteurs',
            'GT2' => 'GT2 - Force de l\'ordre, justice et santé',
            'GT3' => 'GT3 - Auteurs de violences',
            'GT4' => 'GT4 - Cellule familiale',
            'GT5' => 'GT5 - Hébergement - logement FVVC et auteurs',
            'GT6' => 'GT6 - Pilotage du schéma',
        ];
        return $labels[$this->category] ?? $this->category;
    }

    public function getSubCategoryLabelAttribute()
    {
        $labels = [
            'SGT1' => 'SGT1 - Sensibilisation & formations et information grand public',
            'SGT2' => 'SGT2 - Coordination acteurs / Outils professionnels',
            'SGT3' => 'SGT3 - Coordination acteurs / Outils professionnels',
            'SGT4' => 'SGT4 - Parcours',
        ];
        return $labels[$this->sub_category] ?? $this->sub_category;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeGt($query, $gt)
    {
        return $query->where('category', $gt);
    }

    public function scopeSgt($query, $sgt)
    {
        return $query->where('sub_category', $sgt);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}