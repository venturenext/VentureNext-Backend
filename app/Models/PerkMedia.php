<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerkMedia extends Model
{
    use HasFactory;

    public $timestamps = false;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'perk_id',
        'media_type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'alt_text',
        'display_order',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'display_order' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function perk()
    {
        return $this->belongsTo(Perk::class);
    }

    /**
     * Scopes
     */
    public function scopeByType($query, $type)
    {
        return $query->where('media_type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    /**
     * Methods
     */
    public function getFullUrl()
    {
        return asset('storage/' . $this->file_path);
    }

    public function getFileSizeFormatted()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
