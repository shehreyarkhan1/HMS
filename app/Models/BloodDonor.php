<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class BloodDonor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'donor_id',
        'donor_type',
        'name',
        'father_name',
        'date_of_birth',
        'gender',
        'blood_group',
        'weight_kg',
        'cnic',
        'phone',
        'email',
        'address',
        'city',
        'is_eligible',
        'ineligibility_reason',
        'eligible_from',
        'last_donation_date',
        'total_donations',
        'next_eligible_date',
        'patient_id',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'last_donation_date' => 'date',
        'next_eligible_date' => 'date',
        'eligible_from' => 'date',
        'is_eligible' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($donor) {
            if (empty($donor->donor_id)) {
                $latest = static::withTrashed()->latest('id')->first();
                $next = $latest ? ((int) substr($latest->donor_id, 4)) + 1 : 1;
                $donor->donor_id = 'DNR-' . str_pad($next, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function donations(): HasMany
    {
        return $this->hasMany(BloodDonation::class, 'donor_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    public function canDonateNow(): bool
    {
        if (!$this->is_eligible)
            return false;
        if ($this->next_eligible_date && $this->next_eligible_date->isFuture())
            return false;
        return true;
    }

    public function scopeEligible($query)
    {
        return $query->where('is_eligible', true)
            ->where(function ($q) {
                $q->whereNull('next_eligible_date')
                    ->orWhere('next_eligible_date', '<=', today());
            });
    }

    public function scopeByBloodGroup($query, string $group)
    {
        return $query->where('blood_group', $group);
    }
}
