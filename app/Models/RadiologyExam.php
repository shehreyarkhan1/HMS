<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RadiologyExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'exam_code',
        'modality_id',
        'body_part_id',
        'price',
        'requires_contrast',
        'contrast_type',
        'requires_preparation',
        'preparation_instructions',
        'turnaround_hours',
        'duration_minutes',
        'clinical_indications',
        'contraindications',
        'requires_consent',
        'is_active',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'requires_contrast' => 'boolean',
        'requires_preparation' => 'boolean',
        'requires_consent' => 'boolean',
        'is_active' => 'boolean',
        'turnaround_hours' => 'integer',
        'duration_minutes' => 'integer',
    ];

    /**
     * Get the modality for this exam
     */
    public function modality(): BelongsTo
    {
        return $this->belongsTo(RadiologyModality::class);
    }

    /**
     * Get the body part for this exam
     */
    public function bodyPart(): BelongsTo
    {
        return $this->belongsTo(RadiologyBodyPart::class);
    }

    /**
     * Get all order items for this exam
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(RadiologyOrderItem::class);
    }

    /**
     * Scope: Only active exams
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by modality
     */
    public function scopeByModality($query, $modalityId)
    {
        return $query->where('modality_id', $modalityId);
    }

    /**
     * Scope: Filter by body part
     */
    public function scopeByBodyPart($query, $bodyPartId)
    {
        return $query->where('body_part_id', $bodyPartId);
    }

    /**
     * Scope: Exams requiring contrast
     */
    public function scopeRequiresContrast($query)
    {
        return $query->where('requires_contrast', true);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'PKR ' . number_format($this->price, 2);
    }

    /**
     * Auto-generate exam code if not provided
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($exam) {
            if (empty($exam->exam_code)) {
                $exam->exam_code = 'RAD-' . str_pad(self::max('id') + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
