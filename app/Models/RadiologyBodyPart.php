<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RadiologyBodyPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'region',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all exams for this body part
     */
    public function exams(): HasMany
    {
        return $this->hasMany(RadiologyExam::class, 'body_part_id');
    }

    /**
     * Scope: Only active body parts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by region
     */
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Auto-generate code if not provided
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bodyPart) {
            if (empty($bodyPart->code)) {
                $bodyPart->code = 'BP-' . strtoupper(substr($bodyPart->name, 0, 4)) . '-' . str_pad(self::max('id') + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
