<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mrn',
        'name',
        'father_name',
        'date_of_birth',
        'gender',
        'blood_group',
        'phone',
        'emergency_contact',
        'emergency_relation',
        'cnic',
        'address',
        'city',
        'patient_type',
        'status',
        'doctor_id',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // ===== AUTO GENERATE MRN =====
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            $last = static::withTrashed()->latest('id')->first();
            $number = $last ? ($last->id + 1) : 1;
            $patient->mrn = 'MRN-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }

    // ===== RELATIONSHIPS =====
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function bed()
    {
        return $this->hasOne(\App\Models\Bed::class);
    }

    // ===== ACCESSORS =====
    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        return strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
    }

    // ===== SCOPES =====
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeOpd($query)
    {
        return $query->where('patient_type', 'OPD');
    }

    public function scopeIpd($query)
    {
        return $query->where('patient_type', 'IPD');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'ilike', "%$term%")
                ->orWhere('mrn', 'ilike', "%$term%")
                ->orWhere('phone', 'like', "%$term%")
                ->orWhere('cnic', 'like', "%$term%");
        });
    }
}
