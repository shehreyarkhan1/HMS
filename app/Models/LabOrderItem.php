<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabOrderItem extends Model
{
   protected $fillable = [
        'lab_order_id', 'lab_test_id', 'lab_sample_id',
        'price', 'discount', 'final_price',
        'status', 'technician_name', 'completed_at',
    ];
 
    protected $casts = [
        'completed_at' => 'datetime',
    ];
 
    // ── Auto calculate final_price ──
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($item) {
            $item->final_price = max(0, $item->price - $item->discount);
        });
 
        // After save, sync parent order total
        static::saved(function ($item) {
            $item->labOrder->syncTotal();
        });
    }
 
    // ── Relationships ──
    public function labOrder()
    {
        return $this->belongsTo(LabOrder::class, 'lab_order_id');
    }
 
    public function labTest()
    {
        return $this->belongsTo(LabTest::class, 'lab_test_id');
    }
 
    public function sample()
    {
        return $this->belongsTo(LabSample::class, 'lab_sample_id');
    }
 
    public function result()
    {
        return $this->hasOne(LabResult::class, 'lab_order_item_id');
    }
 
    // ── Accessors ──
    public function getHasResultAttribute(): bool
    {
        return $this->result()->exists();
    }
 
    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'Completed';
    }

}
