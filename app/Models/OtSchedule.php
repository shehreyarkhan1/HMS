<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class OtSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'surgery_id',
        'patient_id',
        'ot_room_id',
        'surgeon_id',
        'anesthesiologist_id',
        'scheduled_date',
        'scheduled_time',
        'estimated_duration_mins',
        'actual_start_time',
        'actual_end_time',
        'surgery_type',
        'priority',
        'anesthesia_type',
        'status',
        'diagnosis',
        'procedure_name',
        'procedure_details',
        'pre_op_instructions',
        'post_op_notes',
        'complications',
        'post_op_destination',
        'consent_obtained',
        'consent_at',
        'consent_by',
        'pre_op_assessment_done',
        'pre_op_assessment_notes',
        'postpone_reason',
        'cancellation_reason',
        'rescheduled_date',
        'booked_by',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'actual_start_time' => 'datetime',
        'actual_end_time' => 'datetime',
        'consent_at' => 'datetime',
        'rescheduled_date' => 'date',
        'consent_obtained' => 'boolean',
        'pre_op_assessment_done' => 'boolean',
    ];

    // ── AUTO-GENERATE SURGERY ID ─────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function ($schedule) {
            if (empty($schedule->surgery_id)) {
                $latest = static::withTrashed()->latest('id')->first();
                $next = $latest ? ((int) substr($latest->surgery_id, 4)) + 1 : 1;
                $schedule->surgery_id = 'SRG-' . str_pad($next, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    // ── RELATIONSHIPS ────────────────────────────────────────────────────

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function otRoom(): BelongsTo
    {
        return $this->belongsTo(OtRoom::class);
    }

    public function surgeon(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'surgeon_id');
    }

    public function anesthesiologist(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'anesthesiologist_id');
    }

    public function bookedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(OtTeam::class);
    }

    // ── COMPUTED ATTRIBUTES ──────────────────────────────────────────────

    public function getActualDurationMinsAttribute(): ?int
    {
        if ($this->actual_start_time && $this->actual_end_time) {
            return (int) $this->actual_start_time->diffInMinutes($this->actual_end_time);
        }
        return null;
    }

    public function getScheduledEndTimeAttribute(): string
    {
        return Carbon::parse($this->scheduled_time)
            ->addMinutes($this->estimated_duration_mins)
            ->format('H:i');
    }

    // ── HELPERS ──────────────────────────────────────────────────────────

    public function statusColor(): string
    {
        return match ($this->status) {
            'Scheduled' => 'primary',
            'Confirmed' => 'info',
            'Preparing' => 'warning',
            'In-Progress' => 'success',
            'Completed' => 'secondary',
            'Postponed' => 'warning',
            'Cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function priorityColor(): string
    {
        return match ($this->priority) {
            'Routine' => 'secondary',
            'Priority' => 'info',
            'Urgent' => 'warning',
            'Emergency' => 'danger',
            default => 'secondary',
        };
    }

    public function isEditable(): bool
    {
        return in_array($this->status, ['Scheduled', 'Confirmed', 'Postponed']);
    }

    // ── SCOPES ───────────────────────────────────────────────────────────

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('scheduled_date', '>=', today())
            ->whereNotIn('status', ['Completed', 'Cancelled']);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
