<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Wards;

class WardNurseAssignment extends Model
{
     protected $fillable = [
        'ward_id',
        'user_id',
        'shift',
        'start_date',
        'end_date',
        'is_active',
        'assigned_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    // ── Relationships ──

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function nurse()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // ── Scopes ──

    public function scopeActiveToday($query)
    {
        return $query
            ->where('is_active', true)
            ->where('start_date', '<=', today())
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', today());
            });
    }
}
