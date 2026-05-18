<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mrn',
        'name',
        'father_name',
        'date_of_birth',
        'gender',
        'blood_group',
        'phone',
        'emergency_contact',
        'emergency_relation',
        'cnic',
        'address',
        'city',
        'patient_type',
        'status',
        'doctor_id',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * ===== AUTO GENERATE MRN (Industry Standard) =====
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            if (empty($patient->mrn)) {
                // Latest patient fetch kar rahe hain chahe wo delete ho chuka ho (withTrashed)
                $lastPatient = static::withTrashed()->latest('id')->first();

                if (!$lastPatient) {
                    $number = 1;
                } else {
                    // MRN-00001 se number nikalne ka tareeka
                    $lastNumber = (int) str_replace('MRN-', '', $lastPatient->mrn);
                    $number = $lastNumber + 1;
                }

                $patient->mrn = 'MRN-' . str_pad($number, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * ===== RELATIONSHIPS =====
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function bed()
    {
        return $this->hasOne(Bed::class);
    }

    /**
     * ===== ACCESSORS =====
     */
    // Age calculate karne ke liye
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }
public function mortuaryRecord() {
    return $this->hasOne(\App\Models\MortuaryRecord::class);
}
    // Name ke initials nikalne ke liye (e.g. Ahmed Ali -> AA)
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $w) {
            $initials .= strtoupper(substr($w, 0, 1));
        }
        return substr($initials, 0, 2);
    }

    /**
     * ===== SCOPES (Professional Filtering) =====
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('patient_type', $type);
    }

    /**
     * Professional Search Scope (Database Agnostic)
     * Yeh MySQL (like) aur PostgreSQL (ilike) dono ko support karega
     */
    public function scopeSearch($query, $term)
    {
        if (empty($term)) return $query;

        // Database driver detect karein
        $driver = $query->getConnection()->getDriverName();
        $operator = ($driver === 'pgsql') ? 'ilike' : 'like';

        return $query->where(function ($q) use ($term, $operator) {
            $q->where('name', $operator, "%{$term}%")
              ->orWhere('mrn', $operator, "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('cnic', 'like', "%{$term}%")
              ->orWhere('city', $operator, "%{$term}%");
        });
    }
}
