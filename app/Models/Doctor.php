<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
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
            $doctor->doctor_id = 'DOC-'.str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }

    // ===== ACCESSORS =====

    /**
     * Returns doctor's full name from linked employee.
     */
    public function getNameAttribute(): string
    {
        return $this->employee
            ? "{$this->employee->first_name} {$this->employee->last_name}"
            : 'Unknown Doctor';
    }

    /**
     * Returns display name with Dr. prefix.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->employee
            ? "Dr. {$this->employee->first_name} {$this->employee->last_name}"
            : 'Unknown';
    }

    /**
     * Returns full display: Dr. Name — Specialization.
     */
    public function getFullDisplayAttribute(): string
    {
        return $this->employee
            ? "Dr. {$this->employee->first_name} {$this->employee->last_name} — {$this->specialization}"
            : 'Unknown';
    }

    /**
     * Returns doctor's initials from employee name.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);

        return strtoupper(
            substr($words[0], 0, 1).
            (isset($words[1]) ? substr($words[1], 0, 1) : '')
        );
    }

    /**
     * Returns full URL to doctor's photo or null.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/'.$this->photo) : null;
    }

    /**
     * Returns doctor's email from linked user account.
     */
    public function getEmailAttribute(): ?string
    {
        return $this->user?->email;
    }

    // ===== RELATIONSHIPS =====

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // ===== SCOPES =====

    /**
     * Filter only active doctors.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter doctors with Available status.
     */
    public function scopeAvailable($query)
    {
        return $query->where('availability', 'Available');
    }

    /**
     * Filter by department.
     */
    public function scopeByDepartment($query, string $dept)
    {
        return $query->where('department', $dept);
    }

    /**
     * Search doctors by name, doctor_id, specialization, or PMDC number.
     * Supports both MySQL (LIKE) and PostgreSQL (ILIKE).
     */
    public function scopeSearch($query, string $term)
    {
        // MySQL: LIKE is case-insensitive by default on utf8_general_ci collation
        // PostgreSQL: requires ILIKE for case-insensitive search
        $operator = config('database.default') === 'pgsql' ? 'ilike' : 'like';

        return $query->where(function ($q) use ($term, $operator) {
            $q->whereHas('employee', function ($eq) use ($term, $operator) {
                $eq->where('first_name', $operator, "%{$term}%")
                    ->orWhere('last_name', $operator, "%{$term}%");
            })
                ->orWhere('doctor_id', $operator, "%{$term}%")
                ->orWhere('specialization', $operator, "%{$term}%")
                ->orWhere('pmdc_number', $operator, "%{$term}%");
        });
    }
}
