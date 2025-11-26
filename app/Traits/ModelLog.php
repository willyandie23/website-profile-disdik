<?php

namespace App\Traits;

use App\Models\AppLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait ModelLog
{
    /**
     * Handle model event
     */
    public static function bootModelLog()
    {
        /**
         * Data creating and updating event
         */
        static::saved(function ($model) {
            // create or update?
            if ($model->wasRecentlyCreated) {
                static::storeLog($model, static::class, 'CREATED');
            } else {
                if (!$model->getChanges()) {
                    return;
                }
                static::storeLog($model, static::class, 'UPDATED');
            }
        });

        /**
         * Data deleting event
         */
        static::deleted(function (Model $model) {
            static::storeLog($model, static::class, 'DELETED');
        });
    }

    /**
     * Generate the model name
     * @param  Model  $model
     * @return string
     */
    public static function getTagName(Model $model)
    {
        return !empty($model->tagName) ? $model->tagName : Str::title(Str::snake(class_basename($model), ' '));
    }

    /**
     * Retrieve the current login user id
     * @return int|string|null
     */
    public static function activeUserId()
    {
        return Auth::guard(static::activeUserGuard())->id();
    }

    /**
     * Retrieve the current login user guard name
     * @return mixed|null
     */
    public static function activeUserGuard()
    {
        foreach (array_keys(config('auth.guards')) as $guard) {

            if (auth()->guard($guard)->check()) {
                return $guard;
            }
        }
        return null;
    }

    /**
     * Store model logs
     * @param $model
     * @param $modelPath
     * @param $action
     */
    public static function storeLog($model, $modelPath, $action)
    {

        $newValues = null;
        $oldValues = null;
        if ($action === 'CREATED') {
            $newValues = $model->getAttributes();
        } elseif ($action === 'UPDATED') {
            $newValues = $model->getChanges();
        }

        if ($action !== 'CREATED') {
            $oldValues = $model->getOriginal();
        }

        $appLog = new AppLog();
        $appLog->system_logable_id = $model->id;
        $appLog->system_logable_type = $modelPath;
        $appLog->user_id = static::activeUserId();
        $appLog->guard_name = static::activeUserGuard();
        $appLog->module_name = static::getTagName($model);
        $appLog->action = $action;
        $appLog->old_value = !empty($oldValues) ? json_encode($oldValues) : null;
        $appLog->new_value = !empty($newValues) ? json_encode($newValues) : null;
        $appLog->ip_address = request()->ip();
        $appLog->save();
    }

    /**
     * Store logs for authentication events (login/logout)
     * @param string $action
     */
    public static function storeAuthLog($action)
    {
        $user = Auth::user();

        $log = new AppLog();
        $log->system_logable_id = $user ? $user->id : null; // Gunakan user_id sebagai fallback
        $log->system_logable_type = "Authentication"; // Tambahkan jika perlu
        $log->user_id = $user ? $user->id : null;
        $log->guard_name = static::activeUserGuard();
        $log->module_name = "Authentication"; // Kategori khusus untuk login/logout
        $log->action = $action;
        $log->old_value = null;
        $log->new_value = null;
        $log->ip_address = request()->ip();
        $log->save();
    }
}
