<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Holiday extends Model
{
     protected $fillable = [
        'name', 'date', 'date_to', 'total_days',
        'type', 'year', 'is_recurring',
        'description', 'is_active',
    ];

    protected $casts = [
        'date'         => 'date',
        'date_to'      => 'date',
        'is_recurring' => 'boolean',
        'is_active'    => 'boolean',
    ];

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', Carbon::today())
            ->orderBy('date');
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public static function isHoliday(Carbon $date): bool
    {
        return static::active()
            ->where(function ($q) use ($date) {
                $q->where('date', $date->toDateString())
                  ->orWhere(function ($q2) use ($date) {
                      $q2->whereNotNull('date_to')
                         ->where('date', '<=', $date->toDateString())
                         ->where('date_to', '>=', $date->toDateString());
                  });
            })->exists();
    }
}
