<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabSampleType extends Model
{

    protected $fillable = [
        'name',
        'code',
        'container_type',
        'color_code',
        'volume_required',
        'requires_fasting',
        'collection_instructions',
        'description',
        'is_active',
    ];

    protected $casts = [
        'requires_fasting' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ── Auto generate code ──
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($s) {
            if (empty($s->code)) {
                $last = static::latest('id')->first();
                $num = $last ? ($last->id + 1) : 1;
                $s->code = 'SMP-' . str_pad($num, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    // ── Relationships ──
    public function tests()
    {
        return $this->hasMany(LabTest::class, 'sample_type_id');
    }

    public function samples()
    {
        return $this->hasMany(LabSample::class, 'sample_type_id');
    }

    // ── Scopes ──
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
