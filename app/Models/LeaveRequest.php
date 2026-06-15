<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequest extends Model
{
    use HasAuditLog,SoftDeletes;

    protected string $auditModule = 'leave Request';

    protected $fillable = [
        'leave_number', 'employee_id', 'leave_type_id',
        'from_date', 'to_date', 'total_days',
        'half_day', 'half_day_type', 'reason',
        'document_path', 'status',
        'reviewed_by', 'reviewed_at', 'review_notes',
        'cancelled_by', 'cancelled_at', 'cancellation_reason',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'half_day' => 'boolean',
        'reviewed_at' => 'datetime',
        'cancelled_at' => 'datetime',
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

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeForYear($query, $year)
    {
        return $query->whereYear('from_date', $year);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'Cancelled';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Approved' => 'success',
            'Rejected' => 'danger',
            'Cancelled' => 'secondary',
            'Revoked' => 'warning',
            default => 'warning',   // Pending
        };
    }

    // ── Boot ──────────────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            if (empty($request->leave_number)) {
                $request->leave_number = 'LR-'.str_pad(
                    self::withTrashed()->max('id') + 1, 5, '0', STR_PAD_LEFT
                );
            }
        });
    }
}
