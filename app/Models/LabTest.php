<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    protected $fillable = [
        'name',
        'test_code',
        'category_id',
        'sample_type_id',
        'price',
        'unit',
        'normal_range',
        'normal_range_male',
        'normal_range_female',
        'normal_range_child',
        'normal_range_elderly',
        'turnaround_hours',
        'method',
        'requires_fasting',
        'is_active',
        'description',
    ];

    protected $casts = [
        'requires_fasting' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ── Auto generate test code ──
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($test) {
            if (empty($test->test_code)) {
                $last = static::latest('id')->first();
                $num = $last ? ($last->id + 1) : 1;
                $test->test_code = 'T-' . str_pad($num, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // ── Relationships ──
    public function category()
    {
        return $this->belongsTo(LabTestCategory::class, 'category_id');
    }

    public function sampleType()
    {
        return $this->belongsTo(LabSampleType::class, 'sample_type_id');
    }

    public function orderItems()
    {
        return $this->hasMany(LabOrderItem::class, 'lab_test_id');
    }

    // ── Accessors ──

    /** Normal range based on patient gender */
    public function getNormalRangeForGender(string $gender): string
    {
        return match (strtolower($gender)) {
            'male' => $this->normal_range_male ?? $this->normal_range ?? '—',
            'female' => $this->normal_range_female ?? $this->normal_range ?? '—',
            default => $this->normal_range ?? '—',
        };
    }

    /** Turnaround in human readable */
    public function getTurnaroundLabelAttribute(): string
    {
        if ($this->turnaround_hours < 1)
            return 'STAT';
        if ($this->turnaround_hours <= 4)
            return $this->turnaround_hours . ' hrs (Urgent)';
        if ($this->turnaround_hours <= 24)
            return $this->turnaround_hours . ' hrs';
        return ceil($this->turnaround_hours / 24) . ' day(s)';
    }

    // ── Scopes ──
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%$term%")
                ->orWhere('test_code', 'like', "%$term%")
                ->orWhere('method', 'like', "%$term%");
        });
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
