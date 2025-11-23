<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Analytic extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'perk_id',
        'event_type',
        'session_id',
        'ip_address',
        'user_agent',
        'referrer',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function perk(): BelongsTo
    {
        return $this->belongsTo(Perk::class);
    }
}
