<?php

namespace App\Traits;

use App\Facades\AuditLog;

/**
 * Kisi bhi Model pe lagao — automatically create/update/delete log ho jaye.
 *
 * Usage:
 *   class Patient extends Model {
 *       use HasAuditLog;
 *       protected string $auditModule = 'Patient';  // optional, defaults to class name
 *   }
 */
trait HasAuditLog
{
    protected static array $auditOriginals = [];

    public static function bootHasAuditLog(): void
    {
        // ── CREATE ──
        static::created(function ($model) {
            AuditLog::created($model, $model->getAuditModule());
        });

        // ✅ Yeh sahi hai (static variable use karo)
        static::updating(function ($model) {
            static::$auditOriginals[spl_object_id($model)] = $model->getOriginal();
        });

        static::updated(function ($model) {
            $key = spl_object_id($model);
            $original = static::$auditOriginals[$key] ?? $model->getOriginal();
            unset(static::$auditOriginals[$key]); // memory clean karo
            AuditLog::updated($model, $model->getAuditModule(), $original);
        });

        // ── DELETE ──
        static::deleted(function ($model) {
            AuditLog::deleted($model, $model->getAuditModule());
        });
    }

    protected function getAuditModule(): string
    {
        // Model mein $auditModule property define kar sakte ho
        // warna class name use hoga e.g. "Patient", "Prescription"
        return property_exists($this, 'auditModule')
            ? $this->auditModule
            : class_basename($this);
    }

    // ── Manual helpers on model instance ──────────────────────

    public function logViewed(): void
    {
        AuditLog::viewed($this, $this->getAuditModule());
    }

    public function logCustom(string $action, string $description, string $severity = 'low'): void
    {
        AuditLog::log($action, $this->getAuditModule(), $description, $this, [], [], [], $severity);
    }
}
