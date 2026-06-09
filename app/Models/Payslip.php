<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Payslip extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payroll_run_id', 'employee_id', 'payslip_number',
        'salary_structure_id',
        // Attendance
        'total_working_days', 'present_days', 'absent_days',
        'late_days', 'half_days', 'leave_days',
        'holiday_days', 'overtime_hours',
        // Earnings
        'basic_salary', 'house_rent_allowance', 'medical_allowance',
        'transport_allowance', 'meal_allowance', 'special_allowance',
        'other_allowance', 'overtime_amount', 'bonus', 'arrears',
        'gross_salary',
        // Deductions
        'income_tax_monthly', 'tax_slab', 'eobi_employee_share',
        'provident_fund', 'loan_deduction', 'absent_deduction',
        'late_deduction', 'other_deduction', 'other_deduction_description',
        'total_deductions',
        // Net
        'net_salary', 'per_day_salary',
        // Payment
        'payment_method', 'bank_account_number', 'bank_name',
        'is_paid', 'paid_on', 'transaction_reference',
        'status', 'notes',
    ];

    protected $casts = [
        'paid_on'        => 'date',
        'is_paid'        => 'boolean',
        'overtime_hours' => 'decimal:2',
        'per_day_salary' => 'decimal:2',
        'gross_salary'   => 'decimal:2',
        'net_salary'     => 'decimal:2',
        'total_deductions' => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryStructure(): BelongsTo
    {
        return $this->belongsTo(SalaryStructure::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Generated' => 'info',
            'Approved'  => 'primary',
            'Paid'      => 'success',
            'Cancelled' => 'danger',
            default     => 'secondary',
        };
    }

    public function getFormattedNetAttribute(): string
    {
        return 'PKR ' . number_format($this->net_salary, 2);
    }

    // ── Boot ──────────────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payslip) {
            if (empty($payslip->payslip_number)) {
                $run = PayrollRun::find($payslip->payroll_run_id);
                $count = static::where('payroll_run_id', $payslip->payroll_run_id)->count() + 1;
                $payslip->payslip_number = 'PS-' . $run->year . '-'
                    . str_pad($run->month, 2, '0', STR_PAD_LEFT) . '-'
                    . str_pad($count, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
