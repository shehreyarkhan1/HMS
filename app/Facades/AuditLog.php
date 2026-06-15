<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Models\ActivityLog log(string $action, string $module, string $description, ?\Illuminate\Database\Eloquent\Model $model = null, array $oldValues = [], array $newValues = [], array $extra = [], ?string $severity = null)
 * @method static \App\Models\ActivityLog created(\Illuminate\Database\Eloquent\Model $model, string $module, string $description = '')
 * @method static \App\Models\ActivityLog updated(\Illuminate\Database\Eloquent\Model $model, string $module, array $original, string $description = '')
 * @method static \App\Models\ActivityLog deleted(\Illuminate\Database\Eloquent\Model $model, string $module, string $description = '')
 * @method static \App\Models\ActivityLog viewed(\Illuminate\Database\Eloquent\Model $model, string $module, string $description = '')
 * @method static \App\Models\ActivityLog login(string $username, bool $success = true)
 * @method static \App\Models\ActivityLog logout()
 * @method static \App\Models\ActivityLog exported(string $module, int $count, string $format = 'CSV')
 * @method static \App\Models\ActivityLog printed(\Illuminate\Database\Eloquent\Model $model, string $module)
 *
 * @see \App\Services\ActivityLogger
 */
class AuditLog extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'audit.log';
    }
}
