<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
class RadiologyModality extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'requires_contrast',
        'requires_preparation',
        'preparation_instructions',
        'average_duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'requires_contrast' => 'boolean',
        'requires_preparation' => 'boolean',
        'is_active' => 'boolean',
        'average_duration_minutes' => 'integer',
    ];

    /**
     * Get all exams for this modality
     */
    public function exams(): HasMany
    {
        return $this->hasMany(RadiologyExam::class, 'modality_id');
    }

    /**
     * Get only active exams
     */
    public function activeExams(): HasMany
    {
        return $this->exams()->where('is_active', true);
    }

    /**
     * Scope: Only active modalities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Auto-generate code if not provided
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modality) {
            if (empty($modality->code)) {
                $modality->code = 'MOD-' . strtoupper(substr($modality->name, 0, 4)) . '-' . str_pad(self::max('id') + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
