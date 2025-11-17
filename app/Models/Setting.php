<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group_name',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Scopes
     */
    public function scopeByGroup($query, $group)
    {
        if ($group) {
            return $query->where('group_name', $group);
        }
        return $query;
    }

    /**
     * Static helper methods
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return static::castValue($setting->value, $setting->type);
        });
    }

    public static function set($key, $value, $type = 'string', $group = 'general')
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group_name' => $group,
            ]
        );

        Cache::forget("setting.{$key}");
        Cache::forget('settings.all');

        return $setting;
    }

    public static function getAll($group = null)
    {
        $cacheKey = $group ? "settings.group.{$group}" : 'settings.all';

        return Cache::remember($cacheKey, 3600, function () use ($group) {
            $query = static::query();

            if ($group) {
                $query->where('group_name', $group);
            }

            return $query->get()->mapWithKeys(function ($setting) {
                return [$setting->key => static::castValue($setting->value, $setting->type)];
            })->toArray();
        });
    }

    public static function forget($key)
    {
        $setting = static::where('key', $key)->first();

        if ($setting) {
            $setting->delete();
            Cache::forget("setting.{$key}");
            Cache::forget('settings.all');
        }

        return true;
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            case 'text':
            case 'string':
            default:
                return $value;
        }
    }

    /**
     * Clear settings cache
     */
    public static function clearCache()
    {
        Cache::flush();
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('settings.all');
        });

        static::deleted(function () {
            Cache::forget('settings.all');
        });
    }
}
