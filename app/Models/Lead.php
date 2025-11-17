<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'perk_id',
        'lead_type',
        'name',
        'email',
        'company',
        'phone',
        'message',
        'metadata',
        'ip_address',
        'user_agent',
        'referrer',
    ];

    protected $casts = [
        'metadata' => 'array',
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
        if ($type) {
            return $query->where('lead_type', $type);
        }
        return $query;
    }

    public function scopeByPerk($query, $perkId)
    {
        if ($perkId) {
            return $query->where('perk_id', $perkId);
        }
        return $query;
    }

    public function scopeDateRange($query, $fromDate, $toDate)
    {
        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%")
                  ->orWhere('company', 'ILIKE', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Methods
     */
    public function getUtmSource()
    {
        return $this->metadata['utm_source'] ?? null;
    }

    public function getUtmMedium()
    {
        return $this->metadata['utm_medium'] ?? null;
    }

    public function getUtmCampaign()
    {
        return $this->metadata['utm_campaign'] ?? null;
    }
}
