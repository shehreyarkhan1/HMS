<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use App\Traits\HasAuditLog;

class Attendance extends Model
{
    use HasAuditLog;
    protected string $auditModule ='Attendance'; // For audit log module name
    protected $fillable = [
        'employee_id', 'date',
        'check_in', 'check_out',
        'working_minutes', 'overtime_minutes', 'late_minutes',
        'status', 'source', 'notes',
        'is_regularized', 'regularized_by', 'regularization_reason',
    ];

    protected $casts = [
        'date'             => 'date',
        'is_regularized'   => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function regularizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'regularized_by');
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeForMonth($query, $month, $year)
    {
        return $query->whereMonth('date', $month)->whereYear('date', $year);
    }

    public function scopePresent($query)
    {
        return $query->whereIn('status', ['Present', 'Late', 'Work From Home']);
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'Absent');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', Carbon::today());
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function getWorkingHoursAttribute(): string
    {
        $hours   = intdiv($this->working_minutes, 60);
        $minutes = $this->working_minutes % 60;
        return "{$hours}h {$minutes}m";
    }

    public function getOvertimeHoursAttribute(): string
    {
        $hours   = intdiv($this->overtime_minutes, 60);
        $minutes = $this->overtime_minutes % 60;
        return "{$hours}h {$minutes}m";
    }

    public function isPresent(): bool
    {
        return in_array($this->status, ['Present', 'Late', 'Half Day', 'Work From Home']);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Present'        => 'success',
            'Late'           => 'warning',
            'Half Day'       => 'info',
            'Work From Home' => 'primary',
            'On Leave'       => 'secondary',
            'Holiday'        => 'info',
            'Weekend'        => 'light',
            'Absent'         => 'danger',
            default          => 'secondary',
        };
    }
}
