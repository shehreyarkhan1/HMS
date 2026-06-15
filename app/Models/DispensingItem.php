<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispensingItem extends Model
{
    use HasAuditLog,HasFactory;

    protected string $auditModule = 'Dispensing Item';

    protected $fillable = [
        'dispensing_id',
        'prescription_item_id',
        'medicine_id',
        'medicine_batch_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // ===== RELATIONSHIPS =====
    public function dispensing()
    {
        return $this->belongsTo(Dispensing::class);
    }

    public function prescriptionItem()
    {
        return $this->belongsTo(PrescriptionItem::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function batch()
    {
        return $this->belongsTo(MedicineBatch::class, 'medicine_batch_id');
    }
}
