<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BloodCrossmatch extends Model
{
    protected $fillable = [
        'crossmatch_id',
        'blood_request_id',
        'blood_donation_id',
        'result',
        'method',
        'performed_at',
        'performed_by',
        'notes',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($c) {
            if (empty($c->crossmatch_id)) {
                $latest = static::latest('id')->first();
                $next = $latest ? ((int) substr($latest->crossmatch_id, 4)) + 1 : 1;
                $c->crossmatch_id = 'CRM-' . str_pad($next, 5, '0', STR_PAD_LEFT);
            }
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

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'performed_by');
    }

    public function resultColor(): string
    {
        return match ($this->result) {
            'Compatible' => 'success',
            'Incompatible' => 'danger',
            default => 'warning',
        };
    }
}
