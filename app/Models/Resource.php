<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
class Resource extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'resources';

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'category',
        'theme',
        'service',
        'download_count',
        'user_id',
        'link_url'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'download_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtenir l'URL complète du fichier pour affichage
     */
    public function getUrlAttribute()
    {
        // Si c'est un lien externe (vous pouvez adapter selon votre logique)
        if ($this->file_type === 'lien') {
            return $this->description;
        }
        
        // Pour les fichiers uploadés (dans storage/app/public)
        return asset('storage/' . $this->file_path);
    }

    /**
     * Vérifier si c'est une image
     */
    public function getIsImageAttribute()
    {
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp'];
        return in_array($this->file_type, $imageTypes);
    }

    /**
     * Vérifier si c'est une vidéo
     */
    public function getIsVideoAttribute()
    {
        $videoTypes = ['mp4', 'webm', 'avi', 'mov', 'mkv', 'flv', 'wmv'];
        return in_array($this->file_type, $videoTypes);
    }

    /**
     * Vérifier si c'est un document
     */
    public function getIsDocumentAttribute()
    {
        $documentTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'odt', 'ods'];
        return in_array($this->file_type, $documentTypes);
    }

    /**
     * Obtenir l'icône Font Awesome selon le type de fichier
     */
    public function getFileIconAttribute()
    {
        if ($this->is_image) {
            return 'fa-file-image text-purple-500';
        }
        
        if ($this->is_video) {
            return 'fa-file-video text-red-500';
        }
        
        $icons = [
            'pdf' => 'fa-file-pdf text-red-500',
            'doc' => 'fa-file-word text-blue-500',
            'docx' => 'fa-file-word text-blue-500',
            'xls' => 'fa-file-excel text-green-500',
            'xlsx' => 'fa-file-excel text-green-500',
            'ppt' => 'fa-file-powerpoint text-orange-500',
            'pptx' => 'fa-file-powerpoint text-orange-500',
            'txt' => 'fa-file-alt text-gray-500',
        ];
        
        return $icons[$this->file_type] ?? 'fa-file text-gray-500';
    }

    /**
     * Formater la taille du fichier
     */
    public function getFormattedSizeAttribute()
    {
        $size = $this->file_size;
        
        if ($size < 1024) {
            return $size . ' Ko';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' Mo';
        } else {
            return round($size / 1048576, 2) . ' Go';
        }
    }

    /**
     * Obtenir le nom du fichier tronqué
     */
    public function getDisplayNameAttribute()
    {
        $name = $this->file_name;
        if (strlen($name) > 40) {
            return substr($name, 0, 20) . '...' . substr($name, -15);
        }
        return $name;
    }

    /**
     * Incrémenter le compteur de téléchargements
     */
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }
    protected $appends = ['file_url'];
    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }
    //methode pour lien hypertexte
    public function getHyperlinkAttribute()
    {
        if ($this->file_type === 'lien') {
            return $this->description;
        }
        return null;
    }
    

}