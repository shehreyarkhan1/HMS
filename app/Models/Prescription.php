<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Prescription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'prescription_number',
        'patient_id',
        'doctor_id',
        'appointment_id',
        'status',
        'prescribed_date',
        'valid_until',
        'diagnosis',
        'notes',
    ];

    protected $casts = [
        'prescribed_date' => 'date',
        'valid_until' => 'date',
    ];

    // ===== AUTO GENERATE RX NUMBER =====
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($m) {
            $last = static::withTrashed()->latest('id')->first();
            $m->prescription_number = 'RX-' . str_pad($last ? $last->id + 1 : 1, 5, '0', STR_PAD_LEFT);
        });
    }

    // ===== RELATIONSHIPS =====
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
    public function dispensings()
    {
        return $this->hasMany(Dispensing::class);
    }

    // ===== SCOPES =====
    public function scopePending($q)
    {
        return $q->where('status', 'Pending');
    }

    public function scopeSearch($q, $term)
    {
        return $q->where(function ($q) use ($term) {
            $q->where('prescription_number', 'ilike', "%$term%")
                ->orWhereHas('patient', fn($p) => $p->where('name', 'ilike', "%$term%")
                    ->orWhere('mrn', 'ilike', "%$term%"));
        });
    }

    // ===== ACCESSORS =====
    public function getIsExpiredAttribute()
    {
        return $this->valid_until && $this->valid_until->isPast();
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'Pending' => 'warning',
            'Partial' => 'info',
            'Dispensed' => 'success',
            'Cancelled' => 'danger',
            default => 'secondary',
        };
    }

    // Update status based on items dispensed
    public function syncStatus()
    {
        $items = $this->items;
        $totalQty = $items->sum('quantity');
        $dispensedQty = $items->sum('dispensed_qty');

        if ($dispensedQty == 0) {
            $this->update(['status' => 'Pending']);
        } elseif ($dispensedQty >= $totalQty) {
            $this->update(['status' => 'Dispensed']);
        } else {
            $this->update(['status' => 'Partial']);
        }
    }
}
