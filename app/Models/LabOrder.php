<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class LabOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'patient_id',
        'doctor_id',
        'appointment_id',
        'order_date',
        'priority',
        'status',
        'total_amount',
        'discount',
        'paid_amount',
        'payment_status',
        'report_delivered',
        'report_delivered_at',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'report_delivered_at' => 'datetime',
        'report_delivered' => 'boolean',
    ];

    // ── Auto generate order number ──
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $last = static::withTrashed()->latest('id')->first();
            $num = $last ? ($last->id + 1) : 1;
            $order->order_number = 'LAB-' . str_pad($num, 5, '0', STR_PAD_LEFT);
        });
    }

    // ── Relationships ──
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function items()
    {
        return $this->hasMany(LabOrderItem::class, 'lab_order_id');
    }

    public function samples()
    {
        return $this->hasMany(LabSample::class, 'lab_order_id');
    }

    // ── Accessors ──
    public function getNetAmountAttribute(): float
    {
        return max(0, $this->total_amount - $this->discount);
    }

    public function getBalanceAttribute(): float
    {
        return max(0, $this->net_amount - $this->paid_amount);
    }

    public function getIsFullyPaidAttribute(): bool
    {
        return $this->balance <= 0;
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'STAT' => '#dc2626',
            'Urgent' => '#d97706',
            default => '#16a34a',
        };
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'Completed';
    }

    // ── Scopes ──
    public function scopeToday($query)
    {
        return $query->whereDate('order_date', today());
    }

    public function scopePending($query)
    {
        return $query->whereNotIn('status', ['Completed', 'Cancelled']);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('order_number', 'like', "%$term%")
                ->orWhereHas(
                    'patient',
                    fn($p) =>
                    $p->where('name', 'like', "%$term%")
                        ->orWhere('mrn', 'like', "%$term%")
                );
        });
    }

    // ── Helpers ──

    /** Recalculate and save total_amount from items */
    public function syncTotal(): void
    {
        $total = $this->items()->sum('final_price');
        $this->update(['total_amount' => $total]);
    }

    /** Update payment_status based on amounts */
    public function syncPaymentStatus(): void
    {
        $status = 'Unpaid';
        if ($this->paid_amount >= $this->net_amount) {
            $status = 'Paid';
        } elseif ($this->paid_amount > 0) {
            $status = 'Partial';
        }
        $this->update(['payment_status' => $status]);
    }

}
