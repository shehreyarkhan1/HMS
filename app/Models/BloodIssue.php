<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class BloodIssue extends Model
{
    protected $fillable = [
        'issue_id',
        'blood_request_id',
        'blood_donation_id',
        'patient_id',
        'blood_group',
        'bag_number',
        'volume_ml',
        'component',
        'issued_at',
        'transfusion_started_at',
        'transfusion_completed_at',
        'reaction_observed',
        'reaction_type',
        'reaction_notes',
        'issued_by',
        'notes',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'transfusion_started_at' => 'datetime',
        'transfusion_completed_at' => 'datetime',
        'reaction_observed' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($i) {
            if (empty($i->issue_id)) {
                $latest = static::latest('id')->first();
                $next = $latest ? ((int) substr($latest->issue_id, 4)) + 1 : 1;
                $i->issue_id = 'BIS-' . str_pad($next, 5, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($i) {
            // Update donation status to Issued
            BloodDonation::where('id', $i->blood_donation_id)->update(['status' => 'Issued']);

            // Deduct from inventory reserved units
            BloodInventory::where('blood_group', $i->blood_group)
                ->where('component', $i->component)
                ->first()
                    ?->issueReservedUnits(1);
        });
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(BloodRequest::class, 'blood_request_id');
    }

    public function donation(): BelongsTo
    {
        return $this->belongsTo(BloodDonation::class, 'blood_donation_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'issued_by');
    }

    public function isTransfusionComplete(): bool
    {
        return $this->transfusion_completed_at !== null;
    }

    public function hasReaction(): bool
    {
        return $this->reaction_observed && $this->reaction_type !== 'None';
    }

    public function reactionSeverity(): string
    {
        return match ($this->reaction_type) {
            'Haemolytic', 'TACO', 'TRALI' => 'critical',
            'Febrile', 'Allergic' => 'moderate',
            default => 'none',
        };
    }
}
