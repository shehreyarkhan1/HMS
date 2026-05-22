<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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

                if (! $lastPatient) {
                    $number = 1;
                } else {
                    // MRN-00001 se number nikalne ka tareeka
                    $lastNumber = (int) str_replace('MRN-', '', $lastPatient->mrn);
                    $number = $lastNumber + 1;
                }

                $patient->mrn = 'MRN-'.str_pad($number, 5, '0', STR_PAD_LEFT);
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

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'patient_id');
    }

    // 2. If you want to count actual PAYMENTS (HasManyThrough)
    // This allows you to go Patient -> Bill -> BillPayment
    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(BillPayment::class, Bill::class);
    }

    // 3. Lab Orders (Matches your schema)
    public function labOrders(): HasMany
    {
        return $this->hasMany(LabOrder::class, 'patient_id');
    }

    // 4. Radiology Orders (Matches your schema)
    public function radiologyOrders(): HasMany
    {
        return $this->hasMany(RadiologyOrder::class, 'patient_id');
    }

    // 5. OT Schedules (Matches your schema)
    public function otSchedules(): HasMany
    {
        return $this->hasMany(OtSchedule::class, 'patient_id');
    }

    // 6. Blood Requests (Matches your schema)
    public function bloodRequests(): HasMany
    {
        return $this->hasMany(BloodRequest::class, 'patient_id');
    }

    // 7. Appointments (Matches your schema)


    // 8. Prescriptions (Matches your schema)
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    /**
     * ===== ACCESSORS =====
     */
    // Age calculate karne ke liye
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function mortuaryRecord()
    {
        return $this->hasOne(MortuaryRecord::class);
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
        if (empty($term)) {
            return $query;
        }

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
