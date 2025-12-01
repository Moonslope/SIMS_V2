<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Log an activity
     *
     * @param string $action
     * @param string $description
     * @param mixed $model
     * @return ActivityLog|null
     */
    public static function log(string $action, string $description, $model = null): ?ActivityLog
    {
        // Only log if user is authenticated
        if (! Auth::check()) {
            return null;
        }

        $logDescription = $description;

        // Add model details if provided
        if ($model) {
            $modelName = class_basename($model);
            $modelId = $model->id ??  'N/A';
            $logDescription = "{$description} - {$modelName} ID: {$modelId}";
        }

        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action_description' => $logDescription,
            'log_date' => now(),
        ]);
    }

    /**
     * Log a create action
     */
    public static function created($model, string $customMessage = null): ?ActivityLog
    {
        $modelName = class_basename($model);
        $message = $customMessage ??  "Created new {$modelName}";
        return self::log('create', $message, $model);
    }

    /**
     * Log an update action
     */
    public static function updated($model, string $customMessage = null): ?ActivityLog
    {
        $modelName = class_basename($model);
        $message = $customMessage ??  "Updated {$modelName}";
        return self::log('update', $message, $model);
    }

    /**
     * Log a delete action
     */
    public static function deleted($model, string $customMessage = null): ?ActivityLog
    {
        $modelName = class_basename($model);
        $message = $customMessage ?? "Deleted {$modelName}";
        return self::log('delete', $message, $model);
    }

    /**
     * Log a login action
     */
    public static function login(): ?ActivityLog
    {
        return self::log('login', 'User logged in');
    }

    /**
     * Log a logout action
     */
    public static function logout(): ?ActivityLog
    {
        return self::log('logout', 'User logged out');
    }

    /**
     * Log a custom action
     */
    public static function custom(string $description): ?ActivityLog
    {
        return self::log('custom', $description);
    }
}
