<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MortuaryRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'mortuary_id',
        'patient_id',
        'death_datetime',
        'death_location',
        'ward',
        'bed_number',
        'immediate_cause',
        'intermediate_cause',
        'underlying_cause',
        'contributing_cause',
        'manner_of_death',
        'declared_by',
        'declared_at',
        'locker_number',
        'body_condition',
        'body_weight_kg',
        'identification_marks',
        'status',
        'postmortem_required',
        'postmortem_ordered_by',
        'postmortem_status',
        'postmortem_started_at',
        'postmortem_completed_at',
        'postmortem_by',
        'postmortem_findings',
        'postmortem_report_number',
        'is_medico_legal',
        'mlc_number',
        'police_station',
        'investigating_officer',
        'fir_number',
        'police_informed_at',
        'nok_name',
        'nok_relation',
        'nok_cnic',
        'nok_phone',
        'nok_informed',
        'nok_informed_at',
        'admitted_by',
        'notes',
    ];

    protected $casts = [
        'death_datetime' => 'datetime',
        'declared_at' => 'datetime',
        'postmortem_started_at' => 'datetime',
        'postmortem_completed_at' => 'datetime',
        'police_informed_at' => 'datetime',
        'nok_informed_at' => 'datetime',
        'postmortem_required' => 'boolean',
        'is_medico_legal' => 'boolean',
        'nok_informed' => 'boolean',
    ];

    // ── AUTO ID ──────────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function ($r) {
            if (empty($r->mortuary_id)) {
                $latest = static::withTrashed()->latest('id')->first();
                $next = $latest ? $latest->id + 1 : 1;
                $r->mortuary_id = 'MTY-'.str_pad($next, 5, '0', STR_PAD_LEFT);
            }
        });

        // Jab mortuary record bane — patient ko Deceased mark karo
        static::created(function ($r) {
            Patient::where('id', $r->patient_id)
                ->update(['status' => 'Deceased']);
        });
    }

    // ── RELATIONSHIPS ────────────────────────────────────────────────
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function declaringDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'declared_by');
    }

    public function postmortemDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'postmortem_by');
    }

    public function admittedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'admitted_by');
    }

    public function deathCertificates(): HasMany
    {
        return $this->hasMany(DeathCertificate::class);
    }

    public function latestCertificate(): HasOne
    {
        return $this->hasOne(DeathCertificate::class)->latestOfMany();
    }

    public function bodyRelease(): HasOne
    {
        return $this->hasOne(BodyReleaseRecord::class);
    }

    public function billItems()
    {
        return $this->hasMany(BillItem::class, 'reference_id')
            ->where('reference_type', 'mortuary_records');
    }
      protected $table = 'mortuary_records';

    /**
     * Relationship for the doctor who declared the death
     */
    public function declaredBy(): BelongsTo
    {
        // 'declared_by' is the column name in your database
        return $this->belongsTo(Doctor::class, 'declared_by');
    }

    /**
     * Relationship for the doctor who performed the postmortem
     */
    public function postmortemBy(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'postmortem_by');
    }

    /**
     * Relationship for the employee who admitted the body
     */


    /**
     * Relationship for the patient
     */


    // ── SCOPES ───────────────────────────────────────────────────────
    public function scopeAdmitted($query)
    {
        return $query->where('status', 'Admitted');
    }

    public function scopeMlc($query)
    {
        return $query->where('is_medico_legal', true);
    }

    public function scopeUnclaimed($query)
    {
        return $query->where('status', 'Unclaimed');
    }

    public function scopePendingPostmortem($query)
    {
        return $query->where('postmortem_status', 'Pending');
    }

    // ── HELPERS / ACCESSORS ──────────────────────────────────────────
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Admitted' => 'secondary',
            'Postmortem Pending' => 'warning',
            'Postmortem Done' => 'info',
            'Certificate Issued' => 'primary',
            'Released' => 'success',
            'Transferred' => 'dark',
            'Unclaimed' => 'danger',
            default => 'secondary',
        };
    }

    public function getMannerColorAttribute(): string
    {
        return match ($this->manner_of_death) {
            'Natural' => 'success',
            'Accidental' => 'warning',
            'Homicidal' => 'danger',
            'Suicidal' => 'danger',
            'Undetermined' => 'secondary',
            default => 'secondary',
        };
    }

    public function getIsReleasedAttribute(): bool
    {
        return $this->status === 'Released';
    }

    public function getHasCertificateAttribute(): bool
    {
        return $this->deathCertificates()->exists();
    }

    public function getDaysInMortuaryAttribute(): int
    {
        $end = $this->bodyRelease?->released_at ?? now();

        return (int) Carbon::parse($this->death_datetime)->diffInDays($end);
    }

    public function canIssueCertificate(): bool
    {
        return in_array($this->status, [
            'Admitted',
            'Postmortem Done',
        ]) && ! $this->is_medico_legal;
    }

    public function canRelease(): bool
    {
        return in_array($this->status, ['Certificate Issued'])
            && $this->deathCertificates()->exists();
    }
}
