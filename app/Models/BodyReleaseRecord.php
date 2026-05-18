<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BodyReleaseRecord extends Model
{
    // No SoftDeletes — release record permanent rehna chahiye

    protected $fillable = [
        'release_id',
        'mortuary_record_id',
        'released_to_name',
        'released_to_cnic',
        'released_to_relation',
        'released_to_phone',
        'released_to_address',
        'witness_1_name',
        'witness_1_cnic',
        'witness_2_name',
        'witness_2_cnic',
        'released_at',
        'released_by',
        'transport_type',
        'vehicle_number',
        'destination',
        'death_certificate_provided',
        'death_certificate_number',
        'belongings_returned',
        'belongings_list',
        'valuables_amount',
        'valuables_returned',
        'police_clearance_obtained',
        'police_clearance_number',
        'notes',
    ];

    protected $casts = [
        'released_at' => 'datetime',
        'death_certificate_provided' => 'boolean',
        'belongings_returned' => 'boolean',
        'valuables_returned' => 'boolean',
        'police_clearance_obtained' => 'boolean',
        'valuables_amount' => 'decimal:2',
    ];

    // ── AUTO ID ──────────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function ($r) {
            if (empty($r->release_id)) {
                $latest = static::latest('id')->first();
                $next = $latest ? $latest->id + 1 : 1;
                $r->release_id = 'BRL-'.str_pad($next, 5, '0', STR_PAD_LEFT);
            }
        });

        // Release hone par mortuary status update karo
        static::created(function ($release) {
            MortuaryRecord::where('id', $release->mortuary_record_id)
                ->update(['status' => 'Released']);
        });
    }

    // ── RELATIONSHIPS ────────────────────────────────────────────────
    public function mortuaryRecord(): BelongsTo
    {
        return $this->belongsTo(MortuaryRecord::class);
    }

    public function releasedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'released_by');
    }

    // ── ACCESSORS ────────────────────────────────────────────────────
    public function getIsCompleteAttribute(): bool
    {
        return $this->death_certificate_provided
            && $this->belongings_returned;
    }

    public function getNeedsPoliceAttribute(): bool
    {
        return $this->mortuaryRecord?->is_medico_legal
            && ! $this->police_clearance_obtained;
    }
}
