<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditLog;
class PayrollRun extends Model
{
      use SoftDeletes,HasAuditLog;
      protected string $auditModule='Payroll rum';

    protected $fillable = [
        'run_number', 'year', 'month', 'month_name',
        'status', 'total_employees',
        'total_gross', 'total_deductions', 'total_net',
        'payment_date', 'processed_at', 'approved_at',
        'created_by', 'approved_by', 'notes',
    ];

    protected $casts = [
        'payment_date'    => 'date',
        'processed_at'    => 'datetime',
        'approved_at'     => 'datetime',
        'total_gross'     => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_net'       => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeDraft($query)      { return $query->where('status', 'Draft'); }
    public function scopeProcessed($query)  { return $query->where('status', 'Processed'); }
    public function scopeApproved($query)   { return $query->where('status', 'Approved'); }
    public function scopePaid($query)       { return $query->where('status', 'Paid'); }

    // ── Helpers ───────────────────────────────────────────────────────

    public function isDraft(): bool     { return $this->status === 'Draft'; }
    public function isProcessed(): bool { return $this->status === 'Processed'; }
    public function isApproved(): bool  { return $this->status === 'Approved'; }
    public function isPaid(): bool      { return $this->status === 'Paid'; }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Draft'      => 'secondary',
            'Processing' => 'warning',
            'Processed'  => 'info',
            'Approved'   => 'primary',
            'Paid'       => 'success',
            'Cancelled'  => 'danger',
            default      => 'secondary',
        };
    }

    // Summary recalculate karo payslips se
    public function recalculateSummary(): void
    {
        $this->total_employees  = $this->payslips()->count();
        $this->total_gross      = $this->payslips()->sum('gross_salary');
        $this->total_deductions = $this->payslips()->sum('total_deductions');
        $this->total_net        = $this->payslips()->sum('net_salary');
        $this->saveQuietly();
    }

    // ── Boot ──────────────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($run) {
            if (empty($run->run_number)) {
                $run->run_number = 'PR-' . $run->year . '-' . str_pad($run->month, 2, '0', STR_PAD_LEFT);
            }
            if (empty($run->month_name)) {
                $run->month_name = \Carbon\Carbon::createFromDate($run->year, $run->month, 1)
                    ->format('F Y');
            }
        });
    }
}
