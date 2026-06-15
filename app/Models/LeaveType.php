<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditLog;

class LeaveType extends Model
{
    use HasAuditLog;
    protected string $auditModule='leave Type';
    protected $fillable = [
        'name', 'code', 'description',
        'days_per_year', 'is_paid', 'carry_forward',
        'max_carry_forward', 'encashable',
        'min_service_days', 'max_consecutive_days',
        'notice_days_required', 'requires_document',
        'document_description', 'applicable_male',
        'applicable_female', 'applicable_employment_types',
        'is_active',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'carry_forward' => 'boolean',
        'encashable' => 'boolean',
        'requires_document' => 'boolean',
        'applicable_male' => 'boolean',
        'applicable_female' => 'boolean',
        'is_active' => 'boolean',
        'applicable_employment_types' => 'array',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function isApplicableTo(Employee $employee): bool
    {
        // Gender check
        if ($employee->gender === 'Male' && ! $this->applicable_male) {
            return false;
        }
        if ($employee->gender === 'Female' && ! $this->applicable_female) {
            return false;
        }

        // Employment type check
        if ($this->applicable_employment_types) {
            if (! in_array($employee->employment_type, $this->applicable_employment_types)) {
                return false;
            }
        }

        return true;
    }
}
