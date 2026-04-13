<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MedicineBatch;
use App\Models\PrescriptionItem;
use App\Models\DispensingItem;
use App\Models\Dispensing;


class Medicine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'medicine_code',
        'name',
        'generic_name',
        'brand',
        'category',
        'unit',
        'purchase_price',
        'sale_price',
        'reorder_level',
        'total_stock',
        'requires_prescription',
        'storage_condition',
        'description',
        'is_active',
    ];

    protected $casts = [
        'requires_prescription' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ── Auto generate medicine code ──
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($medicine) {
            $last = static::withTrashed()->latest('id')->first();
            $num = $last ? ($last->id + 1) : 1;
            $medicine->medicine_code = 'MED-' . str_pad($num, 5, '0', STR_PAD_LEFT);
        });
    }

    // ── Relationships ──
    public function batches()
    {
        return $this->hasMany(MedicineBatch::class);
    }

    public function activeBatches()
    {
        return $this->hasMany(MedicineBatch::class)
            ->where('status', 'Active')
            ->where('quantity_in_stock', '>', 0)
            ->orderBy('expiry_date', 'asc'); // FEFO: First Expire First Out
    }

    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    // ── Accessors ──
    public function getIsLowStockAttribute(): bool
    {
        return $this->total_stock <= $this->reorder_level;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->total_stock <= 0;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->is_out_of_stock)
            return 'Out of Stock';
        if ($this->is_low_stock)
            return 'Low Stock';
        return 'In Stock';
    }

    // ── Scopes ──
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('total_stock', '<=', 'reorder_level')
            ->where('total_stock', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('total_stock', 0);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%$term%")
                ->orWhere('generic_name', 'like', "%$term%")
                ->orWhere('medicine_code', 'like', "%$term%")
                ->orWhere('brand', 'like', "%$term%");
        });
    }

    // ── Update total_stock from all active batches ──
    public function syncStock(): void
    {
        $this->update([
            'total_stock' => $this->batches()
                ->where('status', 'Active')
                ->sum('quantity_in_stock'),
        ]);
    }
}
