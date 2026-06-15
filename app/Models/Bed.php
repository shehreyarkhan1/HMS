<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use HasAuditLog, HasFactory;

    protected string $auditModule = 'Bed'; // For audit log module name

    protected $fillable = [
        'bed_number', 'ward_id', 'type', 'status',
        'patient_id', 'admitted_at', 'discharged_at', 'notes',
    ];

    protected $casts = [
        'admitted_at' => 'date',
        'discharged_at' => 'date',
    ];

    // ===== RELATIONSHIPS =====
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // ===== SCOPES =====
    public function scopeAvailable($query)
    {
        return $query->where('status', 'Available');
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'Occupied');
    }

    // ===== HELPERS =====
    public function assignPatient(Patient $patient)
    {
        $this->update([
            'status' => 'Occupied',
            'patient_id' => $patient->id,
            'admitted_at' => now(),
            'discharged_at' => null,
        ]);

        $patient->update(['status' => 'Admitted']);
    }

    public function discharge()
    {
        $patient = $this->patient;

        $this->update([
            'status' => 'Available',
            'patient_id' => null,
            'discharged_at' => now(),
        ]);

        if ($patient) {
            $patient->update(['status' => 'Discharged']);
        }
    }
}
