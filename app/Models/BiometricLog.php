<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
class BiometricLog extends Model
{
    protected $fillable = [
        'machine_serial',
        'enroll_number',
        'employee_id',
        'punch_time',
        'in_out_mode',
        'verify_mode',
        'is_processed',
        'error_note',
    ];

    protected $casts = [
        'punch_time' => 'datetime',
        'is_processed' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // ── Scope: unprocessed (unmapped employees) ───────────────────────
    public function scopeUnprocessed($query)
    {
        return $query->where('is_processed', false);
    }

    // ── Scope: today's logs ───────────────────────────────────────────
    public function scopeToday($query)
    {
        return $query->whereDate('punch_time', today());
    }
}
