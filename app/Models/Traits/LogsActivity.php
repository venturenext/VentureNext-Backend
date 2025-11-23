<?php

namespace App\Models\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity($model, 'created');
        });

        static::updated(function ($model) {
            self::logActivity($model, 'updated');
        });

        static::deleted(function ($model) {
            self::logActivity($model, 'deleted');
        });
    }

    protected static function logActivity($model, $action)
    {
        $user = Auth::user();

        // Only log if user is authenticated (admin action)
        if (!$user) {
            return;
        }

        $modelName = null;
        if (method_exists($model, 'getActivityName')) {
            $modelName = $model->getActivityName();
        } elseif (isset($model->title)) {
            $modelName = $model->title;
        } elseif (isset($model->name)) {
            $modelName = $model->name;
        }

        $changes = null;
        if ($action === 'updated' && method_exists($model, 'getDirty')) {
            $dirty = $model->getDirty();
            // Remove timestamps from changes
            unset($dirty['created_at'], $dirty['updated_at']);
            if (!empty($dirty)) {
                $changes = $dirty;
            }
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'model_type' => class_basename($model),
            'model_id' => $model->id,
            'model_name' => $modelName,
            'changes' => $changes,
        ]);
    }
}
