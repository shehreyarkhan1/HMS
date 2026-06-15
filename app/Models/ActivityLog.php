<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
      protected $fillable = [
        'user_id', 'user_name', 'user_role',
        'action', 'module', 'description',
        'model_type', 'model_id',
        'old_values', 'new_values', 'extra',
        'ip_address', 'user_agent', 'url', 'method',
        'severity',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'extra'      => 'array',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ────────────────────────────────────────────────

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    public function scopeForAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // ── Helpers ───────────────────────────────────────────────

    public function getSeverityBadgeAttribute(): string
    {
        return match ($this->severity) {
            'critical' => 'danger',
            'high'     => 'warning',
            'medium'   => 'info',
            default    => 'secondary',
        };
    }

    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'created'  => 'bi-plus-circle-fill text-success',
            'updated'  => 'bi-pencil-fill text-warning',
            'deleted'  => 'bi-trash-fill text-danger',
            'viewed'   => 'bi-eye-fill text-info',
            'login'    => 'bi-box-arrow-in-right text-primary',
            'logout'   => 'bi-box-arrow-right text-secondary',
            'exported' => 'bi-download text-info',
            'printed'  => 'bi-printer-fill text-secondary',
            default    => 'bi-activity text-muted',
        };
    }
}
