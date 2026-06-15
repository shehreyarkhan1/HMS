<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    // ── Severity levels ────────────────────────────────────────────
    // Low:      view, list, print
    // Medium:   create, update
    // High:     delete, export, login fail
    // Critical: bulk delete, role change, settings change, auth events

    protected array $actionSeverity = [
        'viewed'         => 'low',
        'listed'         => 'low',
        'printed'        => 'low',
        'exported'       => 'high',
        'created'        => 'medium',
        'updated'        => 'medium',
        'deleted'        => 'high',
        'bulk_deleted'   => 'critical',
        'login'          => 'medium',
        'logout'         => 'low',
        'login_failed'   => 'high',
        'role_changed'   => 'critical',
        'password_reset' => 'critical',
        'settings_changed' => 'critical',
        'dispensed'      => 'medium',
        'discharged'     => 'medium',
    ];

    // Fields jo log mein KABHI nahi aane chahiye (sensitive)
    protected array $hiddenFields = [
        'password', 'password_confirmation',
        'token', 'remember_token',
        'secret', 'api_key',
    ];

    // ── Main log method ────────────────────────────────────────────

    public function log(
        string  $action,
        string  $module,
        string  $description,
        ?Model  $model    = null,
        array   $oldValues = [],
        array   $newValues = [],
        array   $extra     = [],
        ?string $severity  = null
    ): ActivityLog {
        $user = Auth::user();

        return ActivityLog::create([
            'user_id'    => $user?->id,
            'user_name'  => $user?->name ?? 'System',
            'user_role'  => $user?->role ?? null,

            'action'      => $action,
            'module'      => $module,
            'description' => $description,

            'model_type' => $model ? get_class($model) : null,
            'model_id'   => $model?->getKey(),

            'old_values' => $this->sanitize($oldValues),
            'new_values' => $this->sanitize($newValues),
            'extra'      => $extra ?: null,

            'ip_address' => Request::ip(),
            'user_agent' => substr(Request::userAgent() ?? '', 0, 255),
            'url'        => Request::fullUrl(),
            'method'     => Request::method(),

            'severity' => $severity ?? $this->actionSeverity[$action] ?? 'low',
        ]);
    }

    // ── Shorthand methods ──────────────────────────────────────────

    public function created(Model $model, string $module, string $description = ''): ActivityLog
    {
        $desc = $description ?: "Created {$module} #{$model->getKey()}";
        return $this->log('created', $module, $desc, $model, [], $model->getAttributes());
    }

    public function updated(Model $model, string $module, array $original, string $description = ''): ActivityLog
    {
        $changed  = array_keys($model->getChanges());
        $oldClean = array_intersect_key($original, array_flip($changed));
        $newClean = array_intersect_key($model->getChanges(), array_flip($changed));

        $desc = $description ?: "Updated {$module} #{$model->getKey()} — changed: " . implode(', ', $changed);
        return $this->log('updated', $module, $desc, $model, $oldClean, $newClean);
    }

    public function deleted(Model $model, string $module, string $description = ''): ActivityLog
    {
        $desc = $description ?: "Deleted {$module} #{$model->getKey()}";
        return $this->log('deleted', $module, $desc, $model, $model->getAttributes());
    }

    public function viewed(Model $model, string $module, string $description = ''): ActivityLog
    {
        $desc = $description ?: "Viewed {$module} #{$model->getKey()}";
        return $this->log('viewed', $module, $desc, $model);
    }

    public function login(string $username, bool $success = true): ActivityLog
    {
        $action   = $success ? 'login' : 'login_failed';
        $severity = $success ? 'medium' : 'high';
        $desc     = $success
            ? "User '{$username}' logged in successfully"
            : "Failed login attempt for '{$username}'";

        return $this->log($action, 'Auth', $desc, null, [], [], [], $severity);
    }

    public function logout(): ActivityLog
    {
        $user = Auth::user();
        return $this->log('logout', 'Auth', "User '{$user?->name}' logged out");
    }

    public function exported(string $module, int $count, string $format = 'CSV'): ActivityLog
    {
        return $this->log('exported', $module, "Exported {$count} {$module} records as {$format}");
    }

    public function printed(Model $model, string $module): ActivityLog
    {
        return $this->log('printed', $module, "Printed {$module} #{$model->getKey()}", $model);
    }

    // ── Helpers ───────────────────────────────────────────────────

    protected function sanitize(array $data): ?array
    {
        if (empty($data)) return null;

        foreach ($this->hiddenFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '*** HIDDEN ***';
            }
        }

        return $data;
    }
}
