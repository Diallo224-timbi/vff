<?php
// app/Models/Schema.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Schema extends Model
{
    protected $table = 'schemas';
    
    protected $fillable = [
        'user_id',
        'category',
        'sub_category',
        'title',
        'description',
        'data',
        'file_path',
        'file_name',
        'file_type',
        'is_image',
        'is_link'
    ];

    protected $casts = [
        'data' => 'array',
        'is_image' => 'boolean',
        'is_link' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accesseur pour obtenir l'URL du fichier
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }
        return null;
    }

    /**
     * Accesseur pour obtenir le lien externe
     */
    public function getLinkUrlAttribute()
    {
        if ($this->is_link && $this->data) {
            $data = is_array($this->data) ? $this->data : json_decode($this->data, true);
            return $data['link_url'] ?? null;
        }
        return null;
    }

    /**
     * Accesseur pour obtenir le nom du GT
     */
    public function getGtLabelAttribute()
    {
        $labels = [
            'GT1' => 'GT1 - Réseau VIF-VC',
            'GT2' => 'GT2 - Force de l\'ordre, justice et santé',
            'GT3' => 'GT3 - Auteurs de violences',
            'GT4' => 'GT4 - Cellule familiale',
            'GT5' => 'GT5 - Hébergement - Logement',
            'GT6' => 'GT6 - Pilotage du schéma'
        ];
        
        return $labels[$this->category] ?? $this->category;
    }

    /**
     * Accesseur pour obtenir le nom du SGT
     */
    public function getSgtLabelAttribute()
    {
        $labels = [
            'SGT1' => 'SGT1 - Sensibilisation & formations',
            'SGT2' => 'SGT2 - Coordination acteurs',
            'SGT3' => 'SGT3 - Outils professionnels',
            'SGT4' => 'SGT4 - Parcours'
        ];
        
        return $labels[$this->sub_category] ?? $this->sub_category;
    }
}