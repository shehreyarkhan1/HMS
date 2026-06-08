<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'duration_minutes',
        'token_number',
        'type',
        'status',
        'reason',
        'notes',
        'consulted_at',
        'follow_up_date',
        'cancelled_by',
        'cancellation_reason',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'follow_up_date' => 'date',
        'consulted_at' => 'datetime',
    ];

    // =============================================
    // RELATIONSHIPS
    // =============================================

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // =============================================
    // ACCESSORS
    // =============================================

    /** Formatted time: 09:30 AM */
    public function getFormattedTimeAttribute(): string
    {
        return $this->appointment_time
            ? Carbon::parse($this->appointment_time)->format('h:i A')
            : '—';
    }

    // Relationship: Ek appointment ke kai bill items ho sakte hain
    public function billItems()
    {
        return $this->hasMany(BillItem::class, 'reference_id')
            ->where('reference_type', 'appointments');
    }

    /** Is appointment today? */
    public function getIsTodayAttribute(): bool
    {
        return $this->appointment_date->isToday();
    }

    /** Is appointment upcoming (future)? */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->appointment_date->isFuture();
    }

    /** Is appointment overdue (past date + time and not completed)? */
    public function getIsOverdueAttribute(): bool
    {
        // 1. Agar status pehle hi khatam ho chuka hai, toh overdue nahi hai
        if (in_array($this->status, ['Completed', 'Cancelled', 'No-show'])) {
            return false;
        }

        // 2. Date aur Time ko ek saath combine karein
        // Hum appointment_date se string nikalenge aur appointment_time usme add kar denge
        try {
            $appointmentFullDateTime = Carbon::parse(
                $this->appointment_date->format('Y-m-d').' '.$this->appointment_time
            );

            // 3. Ab check karein ke kya yeh mukammal waqt (Date + Time) guzar chuka hai?
            return $appointmentFullDateTime->isPast();
        } catch (\Exception $e) {
            // Agar time format mein koi masla ho toh purana tareeka backup ke taur par
            return $this->appointment_date->isPast();
        }
    }

    /** Human-readable date: Mon, 30 Mar 2026 */
    public function getFormattedDateAttribute(): string
    {
        return $this->appointment_date->format('D, d M Y');
    }

    // =============================================
    // SCOPES
    // =============================================

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('appointment_date', '>=', today());
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'Scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    // =============================================
    // HELPERS
    // =============================================

    /**
     * Generate next token for a doctor on a given date.
     */
    public static function nextToken(int $doctorId, string $date): int
    {
        $last = static::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->max('token_number');

        return ($last ?? 0) + 1;
    }

    /**
     * Check if a doctor has a slot conflict on date+time.
     */
    public static function hasConflict(int $doctorId, string $date, string $time, ?int $excludeId = null): bool
    {
        $query = static::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->whereTime('appointment_time', $time)
            ->whereNotIn('status', ['Cancelled', 'No-show']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
