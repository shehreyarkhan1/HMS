<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RadiologyOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'radiology_order_id',
        'radiology_exam_id',
        'price',
        'discount',
        'final_price',
        'status',
        'scanned_at',
        'technician_name',
        'equipment_used',
        'contrast_used',
        'contrast_agent',
        'contrast_dose_ml',
        'contrast_reaction',
        'contrast_reaction_notes',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'contrast_dose_ml' => 'decimal:2',
        'contrast_used' => 'boolean',
        'contrast_reaction' => 'boolean',
    ];

    /**
     * Get the parent order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(RadiologyOrder::class, 'radiology_order_id');
    }

    /**
     * Get the exam details
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(RadiologyExam::class, 'radiology_exam_id');
    }

    /**
     * Get the report for this item
     */
    public function report(): HasOne
    {
        return $this->hasOne(RadiologyReport::class);
    }

    /**
     * Get all images for this item
     */
    public function images(): HasMany
    {
        return $this->hasMany(RadiologyImage::class);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Completed scans
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Scan Completed');
    }

    /**
     * Scope: Items with contrast
     */
    public function scopeWithContrast($query)
    {
        return $query->where('contrast_used', true);
    }

    /**
     * Check if reported
     */
    public function isReported(): bool
    {
        return $this->status === 'Reported' && $this->report()->exists();
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'PKR ' . number_format($this->final_price, 2);
    }
}
