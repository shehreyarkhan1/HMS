<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientDischarge extends Model
{
    use HasAuditLog;

    protected string $auditModule = 'Discharge';

    protected $fillable = [
        'patient_id', 'bed_id', 'doctor_id', 'processed_by',
        'discharge_number', 'admitted_date', 'discharge_date', 'total_days',
        'discharge_type', 'condition_at_discharge',
        'admission_diagnosis', 'final_diagnosis', 'treatment_summary',
        'procedures_done', 'discharge_instructions',
        'medications_on_discharge', 'diet_instructions',
        'activity_instructions', 'follow_up_date', 'follow_up_with',
        'notes', 'status', 'finalized_at',
    ];

    protected $casts = [
        'admitted_date' => 'date',
        'discharge_date' => 'date',
        'follow_up_date' => 'date',
        'finalized_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    protected static function booted(): void
    {
        static::creating(function ($d) {
            $last = static::max('id') ?? 0;
            $d->discharge_number = 'DC-'.str_pad($last + 1, 5, '0', STR_PAD_LEFT);

            // Auto calculate total days
            if ($d->admitted_date && $d->discharge_date) {
                $d->total_days = Carbon::parse($d->admitted_date)
                    ->diffInDays(Carbon::parse($d->discharge_date));
            }
        });
    }
}
