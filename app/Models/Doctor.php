<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'doctor_id',
        'name',
        'specialization',
        'qualification',
        'phone',
        'email',
        'cnic',
        'gender',
        'department',
        'consultation_fee',
        'availability',
        'shift',
        'shift_start',
        'shift_end',
        'bio',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'consultation_fee' => 'decimal:2',
    ];

    // ===== AUTO GENERATE DOCTOR ID =====
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($doctor) {
            $last = static::withTrashed()->latest('id')->first();
            $number = $last ? ($last->id + 1) : 1;
            $doctor->doctor_id = 'DOC-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }

    // ===== RELATIONSHIPS =====
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // ===== ACCESSORS =====
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        return strtoupper(
            substr($words[0], 0, 1) .
            (isset($words[1]) ? substr($words[1], 0, 1) : '')
        );
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : null;
    }

    // ===== SCOPES =====
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('availability', 'Available');
    }

    public function scopeByDepartment($query, $dept)
    {
        return $query->where('department', $dept);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'ilike', "%$term%")
                ->orWhere('doctor_id', 'ilike', "%$term%")
                ->orWhere('specialization', 'ilike', "%$term%")
                ->orWhere('department', 'ilike', "%$term%");
        });
    }
}
