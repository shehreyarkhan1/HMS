<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OtRoom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'room_code',
        'name',
        'room_type',
        'status',
        'has_anesthesia_machine',
        'has_ventilator',
        'has_laparoscopy',
        'has_c_arm',
        'is_laminar_flow',
        'equipment_notes',
        'floor',
        'block',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'has_anesthesia_machine' => 'boolean',
        'has_ventilator' => 'boolean',
        'has_laparoscopy' => 'boolean',
        'has_c_arm' => 'boolean',
        'is_laminar_flow' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ── RELATIONSHIPS ────────────────────────────────────────────────────

    public function schedules(): HasMany
    {
        return $this->hasMany(OtSchedule::class);
    }

    public function activeSchedules(): HasMany
    {
        return $this->hasMany(OtSchedule::class)
            ->whereIn('status', ['Scheduled', 'Confirmed', 'Preparing', 'In-Progress']);
    }

    // ── HELPERS ──────────────────────────────────────────────────────────

    public function isAvailable(): bool
    {
        return $this->status === 'Available' && $this->is_active;
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'Available' => 'success',
            'Occupied' => 'danger',
            'Cleaning' => 'warning',
            'Maintenance' => 'warning',
            'Out of Service' => 'secondary',
            default => 'secondary',
        };
    }

    // ── SCOPES ───────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'Available')->where('is_active', true);
    }
}
