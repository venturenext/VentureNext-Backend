<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerkStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'perk_id',
        'view_count',
        'unique_views',
        'last_viewed_at',
        'claim_count',
        'last_claimed_at',
        'coupon_copy_count',
        'external_link_clicks',
        'conversion_rate',
        'average_time_on_page',
    ];

    protected $casts = [
        'view_count' => 'integer',
        'unique_views' => 'integer',
        'claim_count' => 'integer',
        'coupon_copy_count' => 'integer',
        'external_link_clicks' => 'integer',
        'conversion_rate' => 'decimal:2',
        'average_time_on_page' => 'integer',
        'last_viewed_at' => 'datetime',
        'last_claimed_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function perk()
    {
        return $this->belongsTo(Perk::class);
    }

    /**
     * Methods
     */
    public function incrementCouponCopy()
    {
        $this->increment('coupon_copy_count');
    }

    public function incrementExternalClick()
    {
        $this->increment('external_link_clicks');
    }

    public function calculateConversionRate()
    {
        if ($this->view_count > 0) {
            $totalConversions = $this->claim_count + $this->coupon_copy_count + $this->external_link_clicks;
            $this->conversion_rate = ($totalConversions / $this->view_count) * 100;
            $this->save();
        }
    }

    /**
     * Scopes
     */
    public function scopeMostViewed($query, $limit = 10)
    {
        return $query->orderByDesc('view_count')->limit($limit);
    }

    public function scopeMostClaimed($query, $limit = 10)
    {
        return $query->orderByDesc('claim_count')->limit($limit);
    }

    public function scopeHighestConversion($query, $limit = 10)
    {
        return $query->orderByDesc('conversion_rate')->limit($limit);
    }
}
