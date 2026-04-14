<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;   

class RadiologyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'radiology_order_item_id',
        'findings',
        'impression',
        'recommendations',
        'comparison',
        'is_critical',
        'critical_notes',
        'critical_notified_at',
        'critical_notified_to',
        'status',
        'reported_by',
        'reported_at',
        'verified_by',
        'verified_at',
        'is_verified',
        'amendment_reason',
        'amended_by',
        'amended_at',
    ];

    protected $casts = [
        'is_critical' => 'boolean',
        'is_verified' => 'boolean',
        'critical_notified_at' => 'datetime',
        'reported_at' => 'datetime',
        'verified_at' => 'datetime',
        'amended_at' => 'datetime',
    ];

    /**
     * Get the order item this report belongs to
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(RadiologyOrderItem::class, 'radiology_order_item_id');
    }

    /**
     * Get the radiologist who reported
     */
    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the radiologist who verified
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get who amended the report
     */
    public function amendedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'amended_by');
    }

    /**
     * Scope: Critical reports
     */
    public function scopeCritical($query)
    {
        return $query->where('is_critical', true);
    }

    /**
     * Scope: Verified reports
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope: Pending verification
     */
    public function scopePendingVerification($query)
    {
        return $query->where('status', 'Pending Verification');
    }

    /**
     * Scope: Draft reports
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'Draft');
    }

    /**
     * Check if report needs verification
     */
    public function needsVerification(): bool
    {
        return $this->status === 'Pending Verification' && !$this->is_verified;
    }

    /**
     * Mark as critical and notify
     */
    public function markAsCritical(string $notes, string $notifiedTo): void
    {
        $this->update([
            'is_critical' => true,
            'critical_notes' => $notes,
            'critical_notified_at' => now(),
            'critical_notified_to' => $notifiedTo,
        ]);
    }

    /**
     * Verify the report
     */
    public function verify(int $verifiedById): void
    {
        $this->update([
            'is_verified' => true,
            'verified_by' => $verifiedById,
            'verified_at' => now(),
            'status' => 'Verified',
        ]);
    }
}
