<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    protected $fillable = [
        'bill_id', 'service_type', 'description',
        'reference_type', 'reference_id',
        'quantity', 'unit_price', 'discount', 'total_price',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    // Calculate total_price before saving
    public static function boot()
    {
        parent::boot();
        static::saving(function ($item) {
            $item->total_price = max(0, ($item->quantity * $item->unit_price) - $item->discount);
        });
    }
    public function reference()
{
    return $this->morphTo();
}
    public static function serviceTypes(): array
    {
        return [
            'Consultation', 'Lab', 'Radiology', 'Pharmacy',
            'Bed Charges', 'OT Charges', 'Blood Bank', 'Service', 'Other',
        ];
    }

    public function serviceBadgeClass(): string
    {
        return match ($this->service_type) {
            'Consultation' => 'bg-primary',
            'Lab' => 'bg-info text-dark',
            'Radiology' => 'bg-warning text-dark',
            'Pharmacy' => 'bg-success',
            'Bed Charges' => 'bg-secondary',
            'OT Charges' => 'bg-danger',
            'Blood Bank' => 'bg-danger',
            default => 'bg-dark',
        };
    }
}
