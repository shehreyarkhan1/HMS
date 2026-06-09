<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveBalance extends Model
{
    protected $fillable = [
        'employee_id', 'leave_type_id', 'year',
        'entitled_days', 'used_days', 'pending_days',
        'remaining_days', 'carried_forward', 'encashed_days',
    ];

    protected $casts = [
        'entitled_days'   => 'decimal:1',
        'used_days'       => 'decimal:1',
        'pending_days'    => 'decimal:1',
        'remaining_days'  => 'decimal:1',
        'carried_forward' => 'decimal:1',
        'encashed_days'   => 'decimal:1',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeCurrentYear($query)
    {
        return $query->where('year', now()->year);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function hasBalance(float $days): bool
    {
        return $this->remaining_days >= $days;
    }

    public function recalculate(): void
    {
        $this->remaining_days = $this->entitled_days
            + $this->carried_forward
            - $this->used_days
            - $this->pending_days
            - $this->encashed_days;

        $this->saveQuietly();
    }
}
