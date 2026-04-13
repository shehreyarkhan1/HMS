<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ward_code',
        'type',
        'total_beds',
        'floor',
        'block',
        'bed_charges',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'bed_charges' => 'decimal:2',
    ];

    // ===== AUTO GENERATE WARD CODE =====
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ward) {
            $last = static::latest('id')->first();
            $number = $last ? ($last->id + 1) : 1;
            $ward->ward_code = 'W-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }

    // ===== RELATIONSHIPS =====
    public function beds()
    {
        return $this->hasMany(Bed::class);
    }

    // ===== ACCESSORS =====
    public function getAvailableBedsCountAttribute()
    {
        return $this->beds()->where('status', 'Available')->count();
    }

    public function getOccupiedBedsCountAttribute()
    {
        return $this->beds()->where('status', 'Occupied')->count();
    }

    public function getOccupancyPercentAttribute()
    {
        if ($this->total_beds == 0)
            return 0;
        return round(($this->occupied_beds_count / $this->total_beds) * 100);
    }

    // ===== SCOPES =====
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
