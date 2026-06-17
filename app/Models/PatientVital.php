<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientVital extends Model
{
    use HasAuditLog;

    protected string $auditModule = 'PatientVitals';

    protected $fillable = [
        'patient_id', 'bed_id', 'recorded_by',
        'temperature', 'temperature_route',
        'pulse_rate', 'pulse_rhythm',
        'respiratory_rate',
        'systolic_bp', 'diastolic_bp', 'bp_position',
        'oxygen_saturation', 'oxygen_delivery',
        'blood_glucose', 'blood_glucose_timing',
        'weight', 'height', 'bmi',
        'pain_score', 'pain_location',
        'gcs_score', 'gcs_eye', 'gcs_verbal', 'gcs_motor',
        'central_venous_pressure', 'urine_output',
        'fluid_intake', 'fluid_output',
        'shift', 'notes', 'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // ── Helpers ────────────────────────────────────────────

    public function getBloodPressureAttribute(): string
    {
        if ($this->systolic_bp && $this->diastolic_bp) {
            return "{$this->systolic_bp}/{$this->diastolic_bp} mmHg";
        }

        return '—';
    }

    public function getBpStatusAttribute(): string
    {
        if (! $this->systolic_bp) {
            return 'unknown';
        }
        if ($this->systolic_bp >= 180 || $this->diastolic_bp >= 120) {
            return 'critical';
        }
        if ($this->systolic_bp >= 140 || $this->diastolic_bp >= 90) {
            return 'high';
        }
        if ($this->systolic_bp < 90 || $this->diastolic_bp < 60) {
            return 'low';
        }

        return 'normal';
    }

    public function getSpO2StatusAttribute(): string
    {
        if (! $this->oxygen_saturation) {
            return 'unknown';
        }
        if ($this->oxygen_saturation < 90) {
            return 'critical';
        }
        if ($this->oxygen_saturation < 95) {
            return 'low';
        }

        return 'normal';
    }

    public function getTempStatusAttribute(): string
    {
        if (! $this->temperature) {
            return 'unknown';
        }
        if ($this->temperature >= 103) {
            return 'critical';
        }
        if ($this->temperature >= 100.4) {
            return 'high';
        }
        if ($this->temperature < 96) {
            return 'low';
        }

        return 'normal';
    }

    // Auto-calculate BMI before saving
    protected static function booted(): void
    {
        static::saving(function ($vital) {
            if ($vital->weight && $vital->height && $vital->height > 0) {
                $heightM = $vital->height / 100;
                $vital->bmi = round($vital->weight / ($heightM * $heightM), 1);
            }
        });
    }
}
