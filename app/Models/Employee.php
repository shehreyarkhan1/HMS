<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Employee extends Model
{
    use HasFactory, SoftDeletes;

    // ── FILLABLE ────────────────────────────────────────────────────
    protected $fillable = [
        // Identification
        'employee_id',
        'badge_number',

        // Personal
        'first_name',
        'last_name',
        'father_name',
        'mother_name',
        'date_of_birth',
        'gender',
        'marital_status',
        'religion',
        'nationality',
        'cnic',
        'cnic_expiry',
        'blood_group',
        'photo',

        // Contact
        'personal_phone',
        'office_phone',
        'personal_email',
        'office_email',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',

        // Address
        'present_address',
        'permanent_address',
        'city',
        'province',
        'postal_code',

        // Employment
        'department',
        'designation',
        'job_grade',
        'employment_type',
        'employment_status',
        'joining_date',
        'confirmation_date',
        'contract_end_date',
        'resignation_date',
        'termination_date',
        'termination_reason',

        // Reporting
        'reporting_manager_id',

        // Shift
        'shift',
        'shift_start',
        'shift_end',
        'weekly_hours',
        'working_days',

        // Education
        'highest_qualification',
        'specialization',
        'institution',
        'graduation_year',

        // Experience
        'total_experience_years',
        'previous_employer',
        'previous_designation',

        // Bank & Salary
        'bank_name',
        'bank_account_number',
        'bank_branch',
        'iban',
        'salary_type',
        'basic_salary',

        // Government
        'ntn_number',
        'eobi_number',
        'socso_number',
        'is_tax_filer',

        // System
        'user_id',
        'has_system_access',

        // Notes
        'notes',
    ];

    // ── CASTS ────────────────────────────────────────────────────────
    protected $casts = [
        'date_of_birth' => 'date',
        'cnic_expiry' => 'date',
        'joining_date' => 'date',
        'confirmation_date' => 'date',
        'contract_end_date' => 'date',
        'resignation_date' => 'date',
        'termination_date' => 'date',
        'working_days' => 'array',
        'basic_salary' => 'decimal:2',
        'is_tax_filer' => 'boolean',
        'has_system_access' => 'boolean',
    ];

    // ── AUTO GENERATE EMPLOYEE ID ────────────────────────────────────
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            $last = static::withTrashed()->latest('id')->first();
            $number = $last ? ($last->id + 1) : 1;
            $employee->employee_id = 'EMP-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }

    // ── RELATIONSHIPS ────────────────────────────────────────────────
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'employee_id', 'id');
    }

    // Reporting manager (self-referencing)
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'reporting_manager_id');
    }

    // Subordinates
    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'reporting_manager_id');
    }

    // System user (optional)
    // Employee.php Model ke andar
    public function user()
    {
        // Kyunke foreign key 'users' table mein hai, isliye 'hasOne' use hoga
        return $this->hasOne(User::class, 'employee_id', 'id');
    }

    // Future tables (uncomment when ready)
    // public function salaries()    { return $this->hasMany(EmployeeSalary::class); }
    // public function leaves()      { return $this->hasMany(EmployeeLeave::class); }
    // public function attendance()  { return $this->hasMany(EmployeeAttendance::class); }
    // public function documents()   { return $this->hasMany(EmployeeDocument::class); }
    // public function increments()  { return $this->hasMany(EmployeeIncrement::class); }

    // ── ACCESSORS ────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(
            substr($this->first_name, 0, 1) .
            substr($this->last_name, 0, 1)
        );
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth?->age;
    }

    public function getServiceYearsAttribute(): int
    {
        return $this->joining_date
            ? (int) $this->joining_date->diffInYears(now())
            : 0;
    }

    public function getServiceMonthsAttribute(): int
    {
        return $this->joining_date
            ? (int) $this->joining_date->diffInMonths(now())
            : 0;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    public function getIsOnProbationAttribute(): bool
    {
        return $this->employment_type === 'Probationary';
    }

    public function getIsContractExpiringSoonAttribute(): bool
    {
        return $this->contract_end_date &&
            $this->contract_end_date->diffInDays(now()) <= 30 &&
            $this->contract_end_date->isFuture();
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->employment_status) {
            'Active' => 'success',
            'On Leave' => 'warning',
            'Suspended' => 'danger',
            'Terminated' => 'danger',
            'Resigned' => 'secondary',
            'Retired' => 'secondary',
            default => 'secondary',
        };
    }

    // ── SCOPES ───────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('employment_status', 'Active');
    }

    public function scopeByDepartment($query, string $dept)
    {
        return $query->where('department', $dept);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('employment_type', $type);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('first_name', 'ilike', "%$term%")
                ->orWhere('last_name', 'ilike', "%$term%")
                ->orWhere('employee_id', 'ilike', "%$term%")
                ->orWhere('cnic', 'like', "%$term%")
                ->orWhere('designation', 'ilike', "%$term%")
                ->orWhere('department', 'ilike', "%$term%");
        });
    }

    public function scopeContractExpiringSoon($query)
    {
        return $query->whereNotNull('contract_end_date')
            ->whereBetween('contract_end_date', [now(), now()->addDays(30)]);
    }

    // ── HELPERS ──────────────────────────────────────────────────────

    public const DEPARTMENTS = [
        'Administration',
        'Human Resources',
        'Finance & Accounts',
        'Information Technology',
        'Clinical — General OPD',
        'Clinical — Cardiology',
        'Clinical — Gynaecology',
        'Clinical — Pediatrics',
        'Clinical — Surgery',
        'Clinical — Orthopedic',
        'Clinical — Neurology',
        'Clinical — ENT',
        'Clinical — Dermatology',
        'Clinical — Radiology',
        'Clinical — Pathology',
        'Clinical — ICU',
        'Clinical — Emergency',
        'Clinical — Psychiatry',
        'Clinical — Urology',
        'Clinical — Ophthalmology',
        'Clinical — Anesthesia',
        'Pharmacy',
        'Laboratory',
        'Nursing',
        'Housekeeping',
        'Security',
        'Maintenance',
        'Ambulance & Transport',
        'Kitchen & Cafeteria',
    ];

    public static function designations(): array
    {
        return [
            // Medical
            'Medical Officer',
            'Senior Medical Officer',
            'Consultant',
            'House Officer',
            'Registrar',
            'Specialist',
            'Anesthesiologist',
            // Nursing
            'Head Nurse',
            'Senior Nurse',
            'Staff Nurse',
            'Nursing Assistant',
            // Allied Health
            'Lab Technician',
            'Radiographer',
            'Pharmacist',
            'Physiotherapist',
            // Admin
            'CEO',
            'CFO',
            'Medical Director',
            'HR Manager',
            'Admin Officer',
            'Receptionist',
            'Data Entry Operator',
            'Medical Record Officer',
            // Support
            'Ward Boy',
            'Sweeper',
            'Security Guard',
            'Driver',
            'Cook',
            'Electrician',
            'Plumber',
            'AC Technician',
        ];
    }
}
