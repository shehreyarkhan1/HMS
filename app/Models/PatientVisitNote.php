<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientVisitNote extends Model
{
    use HasAuditLog;

    protected string $auditModule = 'DoctorVisits';

    protected $fillable = [
        'patient_id', 'bed_id', 'doctor_id',
        'subjective', 'objective', 'assessment', 'plan',
        'examination_findings', 'diagnosis_codes',
        'follow_up_instructions', 'is_discharge_ready',
        'visit_type', 'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'diagnosis_codes' => 'array',
        'is_discharge_ready' => 'boolean',
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

    protected static function booted(): void
    {
        static::creating(function ($note) {
            $note->visited_at = $note->visited_at ?? now();
        });
    }
}
