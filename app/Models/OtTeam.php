<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class OtTeam extends Model
{
    protected $fillable = [
        'ot_schedule_id',
        'role',
        'doctor_id',
        'employee_id',
        'notes',
    ];

    // ── RELATIONSHIPS ────────────────────────────────────────────────────

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(OtSchedule::class, 'ot_schedule_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // ── HELPERS ──────────────────────────────────────────────────────────

    public function getMemberNameAttribute(): string
    {
        if ($this->doctor && $this->doctor->employee) {
            $emp = $this->doctor->employee;
            return 'Dr. ' . $emp->first_name . ' ' . $emp->last_name;
        }
        if ($this->employee) {
            return $this->employee->first_name . ' ' . $this->employee->last_name;
        }
        return '—';
    }
}
