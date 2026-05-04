<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BloodInventory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'blood_group',
        'component',
        'units_available',
        'units_reserved',
        'minimum_threshold',
        'last_updated_at',
    ];

    protected $casts = [
        'last_updated_at' => 'datetime',
    ];

    /**
     * Add units to inventory after donation passes screening
     */
    public static function addUnits(string $bloodGroup, string $component, int $quantity = 1): void
    {
        $inventory = static::firstOrCreate(
            ['blood_group' => $bloodGroup, 'component' => $component],
            ['units_available' => 0, 'units_reserved' => 0, 'minimum_threshold' => 2]
        );

        $inventory->increment('units_available', $quantity);
        $inventory->update(['last_updated_at' => now()]);
    }

    /**
     * Deduct units when bag is discarded/failed screening
     */
    public static function deductUnits(string $bloodGroup, string $component, int $quantity = 1): void
    {
        $inventory = static::where('blood_group', $bloodGroup)
            ->where('component', $component)
            ->first();

        if ($inventory && $inventory->units_available >= $quantity) {
            $inventory->decrement('units_available', $quantity);
            $inventory->update(['last_updated_at' => now()]);
        }
    }

    /**
     * Reserve units during cross-match
     */
    public function reserveUnits(int $quantity = 1): bool
    {
        if ($this->units_available >= $quantity) {
            $this->decrement('units_available', $quantity);
            $this->increment('units_reserved', $quantity);
            $this->update(['last_updated_at' => now()]);
            return true;
        }
        return false;
    }

    /**
     * Issue reserved units to patient
     */
    public function issueReservedUnits(int $quantity = 1): void
    {
        if ($this->units_reserved >= $quantity) {
            $this->decrement('units_reserved', $quantity);
            $this->update(['last_updated_at' => now()]);
        }
    }

    /**
     * Release reserved units back to available (e.g., incompatible cross-match)
     */
    public function releaseReservedUnits(int $quantity = 1): void
    {
        if ($this->units_reserved >= $quantity) {
            $this->decrement('units_reserved', $quantity);
            $this->increment('units_available', $quantity);
            $this->update(['last_updated_at' => now()]);
        }
    }

    /**
     * Check if stock is below minimum threshold
     */
    public function isLowStock(): bool
    {
        return $this->units_available <= $this->minimum_threshold;
    }

    /**
     * Check if critically low (zero stock)
     */
    public function isCritical(): bool
    {
        return $this->units_available === 0;
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('units_available <= minimum_threshold');
    }

    public function scopeCriticalStock($query)
    {
        return $query->where('units_available', 0);
    }
}
