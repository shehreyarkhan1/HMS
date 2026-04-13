<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    protected $fillable = [
        'lab_order_item_id',
        'result_value',
        'unit',
        'normal_range',
        'flag',
        'is_abnormal',
        'previous_value',
        'previous_date',
        'remarks',
        'reported_at',
        'verified_by',
        'verified_at',
        'is_verified',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'verified_at' => 'datetime',
        'previous_date' => 'date',
        'is_abnormal' => 'boolean',
        'is_verified' => 'boolean',
    ];

    // ── Relationships ──
    public function orderItem()
    {
        return $this->belongsTo(LabOrderItem::class, 'lab_order_item_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'verified_by');
    }

    // ── Accessors ──
    public function getFlagColorAttribute(): string
    {
        return match ($this->flag) {
            'High', 'Critical High' => '#dc2626',
            'Low', 'Critical Low' => '#1d4ed8',
            'Normal' => '#16a34a',
            default => '#94a3b8',
        };
    }

    public function getFlagBgAttribute(): string
    {
        return match ($this->flag) {
            'High', 'Critical High' => '#fee2e2',
            'Low', 'Critical Low' => '#dbeafe',
            'Normal' => '#dcfce7',
            default => '#f1f5f9',
        };
    }

    public function getIsCriticalAttribute(): bool
    {
        return in_array($this->flag, ['Critical High', 'Critical Low']);
    }

    // ── Helpers ──

    /** Auto-set flag based on value vs normal range */
    public function autoSetFlag(): void
    {
        if (!$this->result_value || !$this->normal_range)
            return;

        // Parse range like "4.5 - 11.0"
        if (preg_match('/^([\d.]+)\s*[-–]\s*([\d.]+)$/', $this->normal_range, $m)) {
            $val = (float) $this->result_value;
            $min = (float) $m[1];
            $max = (float) $m[2];

            if ($val < $min) {
                $flag = $val < ($min * 0.7) ? 'Critical Low' : 'Low';
            } elseif ($val > $max) {
                $flag = $val > ($max * 1.3) ? 'Critical High' : 'High';
            } else {
                $flag = 'Normal';
            }

            $this->update([
                'flag' => $flag,
                'is_abnormal' => $flag !== 'Normal',
            ]);
        }
    }

}
