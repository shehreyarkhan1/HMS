<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Bed;
use App\Models\PatientVital;
use App\Models\PatientNursingNote;
use App\Models\PatientDoctorOrder;
use App\Models\PatientVisitNote;
use App\Models\PatientDischarge;


class Patient extends Model
{
    use HasAuditLog;

    protected string $auditModule = 'Patient'; // For audit log module name

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
                $lastPatient = static::withTrashed()->latest('id')->first();

                if (! $lastPatient) {
                    $number = 1;
                } else {
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
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function bed(): HasOne
    {
        return $this->hasOne(Bed::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'patient_id');
    }

    public function labOrders(): HasMany
    {
        // latest() yahan add kar diya taake humesha new records pehle ayein
        return $this->hasMany(LabOrder::class, 'patient_id')->latest();
    }

    public function radiologyOrders(): HasMany
    {
        return $this->hasMany(RadiologyOrder::class, 'patient_id')->latest();
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class, 'patient_id')->latest();
    }

    public function otSchedules(): HasMany
    {
        return $this->hasMany(OtSchedule::class, 'patient_id');
    }

    public function bloodRequests(): HasMany
    {
        return $this->hasMany(BloodRequest::class, 'patient_id');
    }

    public function mortuaryRecord(): HasOne
    {
        return $this->hasOne(MortuaryRecord::class);
    }


// Existing Patient model ke andar add karo:

    // Active bed (current)


    // All beds history
    public function beds()
    {
        return $this->hasMany(Bed::class);
    }

    public function vitals()
    {
        return $this->hasMany(PatientVital::class)->latest('recorded_at');
    }

    public function nursingNotes()
    {
        return $this->hasMany(PatientNursingNote::class)->latest('noted_at');
    }

    public function doctorOrders()
    {
        return $this->hasMany(PatientDoctorOrder::class)->latest('ordered_at');
    }

    public function visitNotes()
    {
        return $this->hasMany(PatientVisitNote::class)->latest('visited_at');
    }

    public function discharges()
    {
        return $this->hasMany(PatientDischarge::class)->latest();
    }



    /**
     * Allows you to go Patient -> Bill -> BillPayment
     */
    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(BillPayment::class, Bill::class);
    }

    /**
     * ===== ACCESSORS =====
     */

    // Age calculate karne ke liye
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
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

    public function scopeSearch($query, $term)
    {
        if (empty($term)) {
            return $query;
        }

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
