<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RadiologyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'radiology_order_item_id',
        'file_path',
        'file_name',
        'file_type',
        'mime_type',
        'file_size',
        'dicom_series_uid',
        'dicom_instance_uid',
        'view_position',
        'dicom_metadata',
        'is_primary',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_primary' => 'boolean',
        'dicom_metadata' => 'array',
    ];

    /**
     * Get the order item
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(RadiologyOrderItem::class, 'radiology_order_item_id');
    }

    /**
     * Scope: Primary images only
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope: DICOM images
     */
    public function scopeDicom($query)
    {
        return $query->where('file_type', 'dicom');
    }

    /**
     * Get file URL
     */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    /**
     * Delete image file when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($image) {
            if (Storage::exists($image->file_path)) {
                Storage::delete($image->file_path);
            }
        });
    }
}
