<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeathCertificate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'certificate_number',
        'mortuary_record_id',
        'certificate_type',
        'purpose',
        'issued_to_name',
        'issued_to_cnic',
        'issued_to_relation',
        'issued_to_phone',
        'issued_to_address',
        'signed_by_doctor',
        'verified_by',
        'issued_by',
        'issued_at',
        'copy_number',
        'total_copies',
        'is_verified',
        'verified_at',
        'fee_charged',
        'fee_paid',
        'bill_id',
        'remarks',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'fee_paid' => 'boolean',
        'fee_charged' => 'decimal:2',
    ];

    // ── AUTO ID ──────────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function ($r) {
            if (empty($r->certificate_number)) {
                $year = now()->format('Y');
                $latest = static::withTrashed()
                    ->whereYear('created_at', $year)
                    ->latest('id')->first();
                $next = $latest ? $latest->id + 1 : 1;
                $r->certificate_number = 'DC-'.$year.'-'.str_pad($next, 5, '0', STR_PAD_LEFT);
            }

            // Copy number auto set
            if (empty($r->copy_number)) {
                $existing = static::where('mortuary_record_id', $r->mortuary_record_id)->count();
                $r->copy_number = $existing + 1;
            }
        });

        // Certificate issue hone par mortuary status update karo
        static::created(function ($cert) {
            MortuaryRecord::where('id', $cert->mortuary_record_id)
                ->update(['status' => 'Certificate Issued']);
        });
    }

    // ── RELATIONSHIPS ────────────────────────────────────────────────
    public function mortuaryRecord(): BelongsTo
    {
        return $this->belongsTo(MortuaryRecord::class);
    }

    public function signingDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'signed_by_doctor');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'verified_by');
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'issued_by');
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function billItems()
    {
        return $this->hasMany(BillItem::class, 'reference_id')
            ->where('reference_type', 'death_certificates');
    }

    // ── ACCESSORS ────────────────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        if ($this->is_verified) {
            return 'Verified';
        }

        return 'Pending Verification';
    }

    public function getStatusColorAttribute(): string
    {
        return $this->is_verified ? 'success' : 'warning';
    }

    public function getIsDuplicateAttribute(): bool
    {
        return $this->copy_number > 1;
    }
}
