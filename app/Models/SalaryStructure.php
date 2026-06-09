<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class SalaryStructure extends Model
{
     use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'basic_salary',
        'house_rent_allowance', 'medical_allowance',
        'transport_allowance', 'meal_allowance',
        'special_allowance', 'other_allowance',
        'other_allowance_description',
        'gross_salary',
        'eobi_applicable', 'eobi_employee_share', 'eobi_employer_share',
        'is_tax_exempt', 'tax_slab', 'income_tax_monthly',
        'provident_fund', 'loan_deduction',
        'other_deduction', 'other_deduction_description',
        'total_deductions', 'net_salary',
        'effective_from', 'effective_to', 'is_current',
        'notes', 'created_by',
    ];

    protected $casts = [
        'effective_from'       => 'date',
        'effective_to'         => 'date',
        'is_current'           => 'boolean',
        'eobi_applicable'      => 'boolean',
        'is_tax_exempt'        => 'boolean',
        'basic_salary'         => 'decimal:2',
        'house_rent_allowance' => 'decimal:2',
        'medical_allowance'    => 'decimal:2',
        'transport_allowance'  => 'decimal:2',
        'meal_allowance'       => 'decimal:2',
        'special_allowance'    => 'decimal:2',
        'other_allowance'      => 'decimal:2',
        'gross_salary'         => 'decimal:2',
        'eobi_employee_share'  => 'decimal:2',
        'eobi_employer_share'  => 'decimal:2',
        'income_tax_monthly'   => 'decimal:2',
        'provident_fund'       => 'decimal:2',
        'loan_deduction'       => 'decimal:2',
        'other_deduction'      => 'decimal:2',
        'total_deductions'     => 'decimal:2',
        'net_salary'           => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    // ── Computed ──────────────────────────────────────────────────────

    public function computeGross(): float
    {
        return (float) (
            $this->basic_salary +
            $this->house_rent_allowance +
            $this->medical_allowance +
            $this->transport_allowance +
            $this->meal_allowance +
            $this->special_allowance +
            $this->other_allowance
        );
    }

    public function computeDeductions(): float
    {
        return (float) (
            $this->income_tax_monthly +
            $this->eobi_employee_share +
            $this->provident_fund +
            $this->loan_deduction +
            $this->other_deduction
        );
    }

    public function computeNet(): float
    {
        return $this->computeGross() - $this->computeDeductions();
    }

    public function recalculate(): void
    {
        $this->gross_salary    = $this->computeGross();
        $this->total_deductions = $this->computeDeductions();
        $this->net_salary      = $this->computeNet();
        $this->saveQuietly();
    }

    // ── Boot ──────────────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        // Jab naya structure current mark ho — purana current false karo
        static::creating(function ($structure) {
            if ($structure->is_current) {
                static::where('employee_id', $structure->employee_id)
                    ->where('is_current', true)
                    ->update(['is_current' => false, 'effective_to' => now()->toDateString()]);
            }
        });

        static::saving(function ($structure) {
            $structure->gross_salary     = $structure->computeGross();
            $structure->total_deductions = $structure->computeDeductions();
            $structure->net_salary       = $structure->computeNet();
        });
    }
}
