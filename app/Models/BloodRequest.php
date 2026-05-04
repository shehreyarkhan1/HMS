<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BloodRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'request_id',
        'patient_id',
        'doctor_id',
        'blood_group',
        'component',
        'units_required',
        'units_approved',
        'urgency',
        'indication',
        'ward',
        'bed_number',
        'patient_hemoglobin',
        'status',
        'rejection_reason',
        'approved_at',
        'fulfilled_at',
        'processed_by',
        'notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'fulfilled_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($r) {
            if (empty($r->request_id)) {
                $latest = static::withTrashed()->latest('id')->first();
                $next = $latest ? ((int) substr($latest->request_id, 4)) + 1 : 1;
                $r->request_id = 'BRQ-' . str_pad($next, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'processed_by');
    }

    public function crossmatches(): HasMany
    {
        return $this->hasMany(BloodCrossmatch::class);
    }

    public function issues(): HasMany
    {
        return $this->hasMany(BloodIssue::class);
    }

    public function urgencyColor(): string
    {
        return match ($this->urgency) {
            'Emergency' => 'danger',
            'Urgent' => 'warning',
            default => 'secondary',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'Fulfilled' => 'success',
            'Approved' => 'info',
            'Rejected' => 'danger',
            'Cancelled' => 'secondary',
            default => 'warning',
        };
    }

    public function isPending(): bool
    {
        return in_array($this->status, ['Pending', 'Under Review']);
    }

    public function canBeApproved(): bool
    {
        return in_array($this->status, ['Pending', 'Under Review']);
    }

    public function canBeFulfilled(): bool
    {
        return in_array($this->status, ['Approved', 'Partially Fulfilled']);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['Pending', 'Under Review']);
    }

    public function scopeUrgent($query)
    {
        return $query->whereIn('urgency', ['Urgent', 'Emergency']);
    }
}
