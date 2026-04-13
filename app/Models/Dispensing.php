<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\PrescriptionItem;
use App\Models\DispensingItem;
use App\Models\Prescription;

class Dispensing extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispense_number',
        'prescription_id',
        'patient_id',
        'dispensed_at',
        'total_amount',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'dispensed_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    // ===== AUTO GENERATE DISPENSE NUMBER =====
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($m) {
            $last = static::latest('id')->first();
            $m->dispense_number = 'DSP-' . str_pad($last ? $last->id + 1 : 1, 5, '0', STR_PAD_LEFT);
        });

        // After dispensing created, sync medicine stock
        static::created(function ($dispensing) {
            foreach ($dispensing->items as $item) {
                $item->medicine->syncStock();
            }
        });
    }

    // ===== RELATIONSHIPS =====
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function items()
    {
        return $this->hasMany(DispensingItem::class);
    }
}
