<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BloodDonation extends Model
{
    use SoftDeletes;
 
    protected $fillable = [
        'donation_id', 'donor_id', 'donation_date', 'donation_time',
        'blood_group', 'volume_ml', 'bag_number', 'component',
        'screening_status', 'hiv_tested', 'hbsag_tested', 'hcv_tested',
        'vdrl_tested', 'malaria_tested', 'screening_notes',
        'status', 'expiry_date', 'collected_by', 'notes',
    ];
 
    protected $casts = [
        'donation_date' => 'date',
        'expiry_date'   => 'date',
        'hiv_tested'    => 'boolean',
        'hbsag_tested'  => 'boolean',
        'hcv_tested'    => 'boolean',
        'vdrl_tested'   => 'boolean',
        'malaria_tested'=> 'boolean',
    ];
 
    protected static function booted(): void
    {
        static::creating(function ($d) {
            if (empty($d->donation_id)) {
                $latest = static::withTrashed()->latest('id')->first();
                $next   = $latest ? ((int) substr($latest->donation_id, 4)) + 1 : 1;
                $d->donation_id = 'DON-' . str_pad($next, 5, '0', STR_PAD_LEFT);
            }
            // Auto set expiry based on component
            if (empty($d->expiry_date)) {
                $days = match($d->component) {
                    'Platelets'             => 5,
                    'Fresh Frozen Plasma'   => 365,
                    'Cryoprecipitate'       => 365,
                    default                 => 35,   // Whole blood / PRBC
                };
                $d->expiry_date = Carbon::parse($d->donation_date)->addDays($days);
            }
        });
 
        // Update donor's last donation & next eligible date
        static::created(function ($d) {
            $donor = BloodDonor::find($d->donor_id);
            if ($donor) {
                $donor->update([
                    'last_donation_date' => $d->donation_date,
                    'next_eligible_date' => Carbon::parse($d->donation_date)->addDays(90),
                    'total_donations'    => $donor->total_donations + 1,
                ]);
            }
            // Update inventory
            BloodInventory::addUnits($d->blood_group, $d->component);
        });
    }
 
    public function donor(): BelongsTo
    {
        return $this->belongsTo(BloodDonor::class, 'donor_id');
    }
 
    public function collectedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'collected_by');
    }
 
    public function crossmatches(): HasMany
    {
        return $this->hasMany(BloodCrossmatch::class);
    }
 
    public function issue(): HasMany
    {
        return $this->hasMany(BloodIssue::class);
    }
 
    public function isExpired(): bool
    {
        return $this->expiry_date->isPast();
    }
 
    public function daysUntilExpiry(): int
    {
        return max(0, today()->diffInDays($this->expiry_date, false));
    }
 
    public function screeningBadgeColor(): string
    {
        return match($this->screening_status) {
            'Passed'   => 'success',
            'Failed'   => 'danger',
            'Discarded'=> 'secondary',
            default    => 'warning',
        };
    }
 
    public function statusColor(): string
    {
        return match($this->status) {
            'Available' => 'success',
            'Reserved'  => 'info',
            'Issued'    => 'secondary',
            'Expired'   => 'warning',
            'Discarded' => 'danger',
            default     => 'secondary',
        };
    }
 
    public function scopeAvailable($query)
    {
        return $query->where('status', 'Available')
            ->where('screening_status', 'Passed')
            ->where('expiry_date', '>', today());
    }
 
    public function scopeExpiringSoon($query, int $days = 3)
    {
        return $query->where('status', 'Available')
            ->whereBetween('expiry_date', [today(), today()->addDays($days)]);
    }
}
