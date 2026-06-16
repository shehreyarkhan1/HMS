<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Doctor;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Traits\HasAuditLog;

class Setting extends Model
{
    use HasAuditLog;
    protected string $auditModule='setting';
    protected $fillable = ['group', 'key', 'value', 'type', 'label', 'description'];

    // ── Core Helpers ────────────────────────────────────────────────

    /**
     * Get a setting value by key.
     * Returns $default if key does not exist.
     *
     * Usage: Setting::get('hospital_name')
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = static::allCached();

        return $settings[$key] ?? $default;
    }

    /**
     * Set (update or create) a setting value by key.
     *
     * Usage: Setting::set('hospital_name', 'New Name')
     */
    public static function set(string $key, mixed $value): void
    {
        static::where('key', $key)->update(['value' => $value]);
        static::clearCache();
    }

    /**
     * Returns all settings as a flat key → value array (cached for 24h).
     */
    public static function allCached(): array
    {
        return Cache::remember('app_settings', 86400, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Returns settings for a specific group.
     */
    public static function byGroup(string $group)
    {
        return static::where('group', $group)->orderBy('id')->get();
    }

    /**
     * Clear the settings cache (call after any update).
     */
    public static function clearCache(): void
    {
        Cache::forget('app_settings');
    }
}
