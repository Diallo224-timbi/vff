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
        'link_url',
        'important',
        // NOUVEAUX CHAMPS
        'category_id',
        'type_id',
        'resource_type',
        'version',
        'status',
        'is_featured',
        'is_important',
        'view_count',
        'published_at',
        'expires_at',
        'meta_keywords',
        'meta_description'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'download_count' => 'integer',
        'view_count' => 'integer',
        'important' => 'boolean',
        'is_featured' => 'boolean',
        'is_important' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $appends = ['file_url', 'file_icon', 'formatted_size', 'status_label'];

    // ============================================
    // RELATIONS EXISTANTES
    // ============================================
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ============================================
    // NOUVELLES RELATIONS
    // ============================================
    public function resourceCategory()
    {
        return $this->belongsTo(ResourceCategory::class, 'category_id');
    }

    public function type()
    {
        return $this->belongsTo(ResourceType::class, 'type_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'resource_tag')->withTimestamps();
    }

    public function workGroups()
    {
        return $this->belongsToMany(WorkGroup::class, 'work_group_resource')
                    ->withPivot('is_essential', 'notes', 'assigned_by')
                    ->withTimestamps();
    }

    public function versions()
    {
        return $this->hasMany(ResourceVersion::class)->orderBy('created_at', 'desc');
    }

    // ============================================
    // ACCESSORS EXISTANTS
    // ============================================
    public function getFileUrlAttribute()
    {
        if ($this->file_type === 'lien' || $this->resource_type === 'link') {
            return $this->link_url ?: $this->description;
        }
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }
        return null;
    }

    public function getFileIconAttribute()
    {
        if ($this->is_image) {
            return 'fa-file-image text-purple-500';
        }
        if ($this->is_video) {
            return 'fa-file-video text-red-500';
        }
        if ($this->is_link) {
            return 'fa-link text-blue-500';
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

    public function getFormattedSizeAttribute()
    {
        $size = $this->file_size;
        if ($size < 1024) {
            return $size . ' B';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return round($size / 1048576, 2) . ' MB';
        }
    }

    public function getIsLinkAttribute()
    {
        return empty($this->file_path) && !empty($this->link_url);
    }

    public function getStatusLabelAttribute()
    {
        return [
            'draft' => 'Brouillon',
            'published' => 'Publié',
            'archived' => 'Archivé',
        ][$this->status] ?? $this->status;
    }

    // ============================================
    // SCOPES
    // ============================================
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where(function($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%");
        });
    }

    // ============================================
    // MÉTHODES EXISTANTES
    // ============================================
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    public static function findSimilar($title, $category, $file_name = null, $link_url = null)
    {
        $query = self::query();
        
        $query->where('title', 'LIKE', '%' . $title . '%');
        $query->where('category', $category);
        
        if ($file_name) {
            $query->where('file_name', $file_name);
        }
        
        if ($link_url) {
            $query->where('link_url', $link_url);
        }
        
        return $query->first();
    }
}