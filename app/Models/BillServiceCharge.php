<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditLog;


class BillServiceCharge extends Model
{
    use HasAuditLog;
    protected string $auditModule = 'Bill Service Charge'; // For audit log module name
    protected $fillable = [
        'name',
        'code',
        'category',
        'blood_component',  // ← yeh add karo
        'default_price',
        'description',
        'is_active',

    ];

    protected $casts = [
        'default_price' => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public static function categories(): array
    {
        return ['Consultation', 'Procedure', 'Bed Charges', 'OT Charges', 'Blood Bank', 'Service', 'Other'];
    }
}
