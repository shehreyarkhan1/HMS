<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientDoctorOrder extends Model
{
    use HasAuditLog;
    protected string $auditModule = 'DoctorOrders';

    protected $fillable = [
        'patient_id', 'bed_id', 'doctor_id', 'acknowledged_by',
        'order_number', 'order_type', 'title', 'details',
        'special_instructions', 'priority', 'status',
        'ordered_at', 'acknowledged_at', 'completed_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'ordered_at'      => 'datetime',
        'acknowledged_at' => 'datetime',
        'completed_at'    => 'datetime',
    ];

    public function patient(): BelongsTo         { return $this->belongsTo(Patient::class); }
    public function bed(): BelongsTo             { return $this->belongsTo(Bed::class); }
    public function doctor(): BelongsTo          { return $this->belongsTo(Doctor::class); }
    public function acknowledgedBy(): BelongsTo  { return $this->belongsTo(User::class, 'acknowledged_by'); }

    public function getPriorityBadgeAttribute(): string
    {
        return match($this->priority) {
            'STAT'   => 'danger',
            'Urgent' => 'warning',
            default  => 'secondary',
        };
    }

    // Auto order number
    protected static function booted(): void
    {
        static::creating(function ($order) {
            $last = static::max('id') ?? 0;
            $order->order_number = 'ORD-' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
            $order->ordered_at   = $order->ordered_at ?? now();
        });
    }
}
