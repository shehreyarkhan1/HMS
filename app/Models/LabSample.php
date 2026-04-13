<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabSample extends Model
{
    protected $fillable = [
        'sample_number', 'lab_order_id', 'sample_type_id', 'status',
        'collected_at', 'received_at', 'processed_at',
        'collected_by', 'rejection_reason', 'notes',
    ];
 
    protected $casts = [
        'collected_at'  => 'datetime',
        'received_at'   => 'datetime',
        'processed_at'  => 'datetime',
    ];
 
    // ── Auto generate sample number ──
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($sample) {
            $last = static::latest('id')->first();
            $num  = $last ? ($last->id + 1) : 1;
            $sample->sample_number = 'SMP-' . str_pad($num, 5, '0', STR_PAD_LEFT);
        });
    }
 
    // ── Relationships ──
    public function labOrder()
    {
        return $this->belongsTo(LabOrder::class, 'lab_order_id');
    }
 
    public function sampleType()
    {
        return $this->belongsTo(LabSampleType::class, 'sample_type_id');
    }
 
    public function orderItems()
    {
        return $this->hasMany(LabOrderItem::class, 'lab_sample_id');
    }
 
    // ── Accessors ──
    public function getIsRejectedAttribute(): bool
    {
        return $this->status === 'Rejected';
    }
 
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Collected'  => '#16a34a',
            'Received'   => '#1d4ed8',
            'In Process' => '#d97706',
            'Completed'  => '#15803d',
            'Rejected'   => '#dc2626',
            default      => '#94a3b8',
        };
    }
}
