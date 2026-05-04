<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',    // <--- Yeh lazmi hona chahiye
        'doctor_id',
        'specialization',
        'qualification',
        'pmdc_number',
        'consultation_fee',
        'availability',
        'doctor_type',
        'sub_department',
        'avg_consultation_mins',
        'available_days',
        'bio',
        'clinical_notes',
        'accepts_new_patients',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'consultation_fee' => 'decimal:2',

        'available_days' => 'array',
    ];
    // ===== AUTO GENERATE DOCTOR ID =====
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($doctor) {
            $last = static::withTrashed()->latest('id')->first();
            $number = $last ? ($last->id + 1) : 1;
            $doctor->doctor_id = 'DOC-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }
    /**
     * Doctor ka full name (employee se)
     */
    public function getNameAttribute()
    {
        return $this->employee
            ? "{$this->employee->first_name} {$this->employee->last_name}"
            : 'Unknown Doctor';
    }

    /**
     * Display name with title: Dr. FirstName LastName
     */
    public function getDisplayNameAttribute()
    {
        return $this->employee
            ? "Dr. {$this->employee->first_name} {$this->employee->last_name}"
            : 'Unknown';
    }

    /**
     * Full display: Dr. Name — Specialization
     */
    public function getFullDisplayAttribute()
    {
        return $this->employee
            ? "Dr. {$this->employee->first_name} {$this->employee->last_name} — {$this->specialization}"
            : 'Unknown';
    }
    // ===== RELATIONSHIPS =====
// Doctor ka name user table se aayega (agar linked user hai toh) — isliye accessor
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    // Doctor ka email bhi user table se aayega (agar linked user hai toh) — isliye accessor
    public function getEmailAttribute()
    {
        return $this->user?->email;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //     public function user()
// {
//     return $this->hasOne(User::class);
// }
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // ===== ACCESSORS =====
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        return strtoupper(
            substr($words[0], 0, 1) .
            (isset($words[1]) ? substr($words[1], 0, 1) : '')
        );
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : null;
    }

    // ===== SCOPES =====
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('availability', 'Available');
    }

    public function scopeByDepartment($query, $dept)
    {
        return $query->where('department', $dept);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'ilike', "%$term%")
                ->orWhere('doctor_id', 'ilike', "%$term%")
                ->orWhere('specialization', 'ilike', "%$term%")
                ->orWhere('department', 'ilike', "%$term%");
        });
    }
}
