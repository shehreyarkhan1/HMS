<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTestCategory extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ── Auto generate code ──
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($cat) {
            if (empty($cat->code)) {
                $last = static::latest('id')->first();
                $num = $last ? ($last->id + 1) : 1;
                $cat->code = 'CAT-' . str_pad($num, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    // ── Relationships ──
    public function tests()
    {
        return $this->hasMany(LabTest::class, 'category_id');
    }

    // ── Scopes ──
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
