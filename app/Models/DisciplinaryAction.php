<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DisciplinaryAction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'action_number', 'employee_id',
        'incident_date', 'incident_type', 'incident_description',
        'action_type', 'action_date', 'action_details',
        'suspension_from', 'suspension_to', 'suspension_days', 'suspension_paid',
        'deduction_amount', 'deduction_month',
        'employee_response', 'response_deadline',
        'response_received', 'response_received_date',
        'status', 'is_appealed', 'appeal_details', 'appeal_outcome',
        'issued_by', 'reviewed_by',
        'notes', 'document_path',
    ];

    protected $casts = [
        'incident_date'         => 'date',
        'action_date'           => 'date',
        'suspension_from'       => 'date',
        'suspension_to'         => 'date',
        'response_deadline'     => 'date',
        'response_received_date' => 'date',
        'suspension_paid'       => 'boolean',
        'response_received'     => 'boolean',
        'is_appealed'           => 'boolean',
        'deduction_amount'      => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'issued_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reviewed_by');
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['Resolved', 'Closed']);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Issued'       => 'warning',
            'Acknowledged' => 'info',
            'Under Review' => 'primary',
            'Resolved'     => 'success',
            'Escalated'    => 'danger',
            'Closed'       => 'secondary',
            default        => 'secondary',
        };
    }

    public function getActionColorAttribute(): string
    {
        return match ($this->action_type) {
            'Verbal Warning'  => 'info',
            'Written Warning' => 'warning',
            'Show Cause Notice' => 'warning',
            'Suspension'      => 'danger',
            'Demotion'        => 'danger',
            'Salary Deduction' => 'warning',
            'Termination'     => 'danger',
            default           => 'secondary',
        };
    }

    // ── Boot ──────────────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($action) {
            if (empty($action->action_number)) {
                $action->action_number = 'DA-' . str_pad(
                    static::withTrashed()->max('id') + 1, 5, '0', STR_PAD_LEFT
                );
            }
        });
    }
}
