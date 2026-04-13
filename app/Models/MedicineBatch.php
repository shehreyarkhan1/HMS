<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class MedicineBatch extends Model
{
    protected $fillable = [
        'medicine_id',
        'batch_number',
        'expiry_date',
        'manufacture_date',
        'quantity_received',
        'quantity_in_stock',
        'purchase_price',
        'supplier_name',
        'supplier_invoice',
        'status',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'manufacture_date' => 'date',
    ];

    // ── Relationships ──
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // ── Accessors ──
    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date->isPast();
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->expiry_date->isBetween(now(), now()->addDays(30));
    }

    public function getDaysToExpiryAttribute(): int
    {
        return (int) now()->diffInDays($this->expiry_date, false);
    }

    // ── Auto update status on save ──
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($batch) {
            // Auto mark expired
            if ($batch->expiry_date->isPast() && $batch->status === 'Active') {
                $batch->updateQuietly(['status' => 'Expired']);
            }
            // Auto mark exhausted
            if ($batch->quantity_in_stock <= 0 && $batch->status === 'Active') {
                $batch->updateQuietly(['status' => 'Exhausted']);
            }
            // Sync parent medicine stock
            $batch->medicine->syncStock();
        });
    }
}
