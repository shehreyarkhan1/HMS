<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Prescription;


class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'medicine_id',
        'dosage',
        'frequency',
        'duration_days',
        'quantity',
        'dispensed_qty',
        'instructions',
    ];

    // ===== RELATIONSHIPS =====
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // ===== ACCESSORS =====
    public function getRemainingQtyAttribute()
    {
        return max(0, $this->quantity - $this->dispensed_qty);
    }

    public function getIsFullyDispensedAttribute()
    {
        return $this->dispensed_qty >= $this->quantity;
    }
}
