<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;   
use Illuminate\Support\Facades\Storage;
class RadiologyConsent extends Model
{
    use HasFactory;

    protected $fillable = [
        'radiology_order_id',
        'consent_type',
        'is_signed',
        'signed_by',
        'relationship',
        'signed_at',
        'witness',
        'signature_path',
        'notes',
    ];

    protected $casts = [
        'is_signed' => 'boolean',
        'signed_at' => 'datetime',
    ];

    /**
     * Get the order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(RadiologyOrder::class, 'radiology_order_id');
    }

    /**
     * Scope: Signed consents only
     */
    public function scopeSigned($query)
    {
        return $query->where('is_signed', true);
    }

    /**
     * Scope: Unsigned consents
     */
    public function scopeUnsigned($query)
    {
        return $query->where('is_signed', false);
    }

    /**
     * Scope: By consent type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('consent_type', $type);
    }

    /**
     * Get signature URL
     */
    public function getSignatureUrlAttribute(): ?string
    {
        return $this->signature_path ? Storage::url($this->signature_path) : null;
    }

    /**
     * Sign the consent
     */
    public function sign(string $signedBy, string $relationship, ?string $witness = null): void
    {
        $this->update([
            'is_signed' => true,
            'signed_by' => $signedBy,
            'relationship' => $relationship,
            'signed_at' => now(),
            'witness' => $witness,
        ]);
    }

    /**
     * Delete signature file when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($consent) {
            if ($consent->signature_path && Storage::exists($consent->signature_path)) {
                Storage::delete($consent->signature_path);
            }
        });
    }
}
