<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientNursingNote extends Model
{
    use HasAuditLog;

    protected string $auditModule = 'NursingNotes';

    protected $fillable = [
        'patient_id', 'bed_id', 'nurse_id',
        'shift', 'note_type', 'note',
        'interventions', 'patient_response',
        'requires_doctor_attention', 'is_urgent',
        'noted_at',
    ];

    protected $casts = [
        'noted_at' => 'datetime',
        'requires_doctor_attention' => 'boolean',
        'is_urgent' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    public function nurse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }
}
