<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class RadiologyOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'patient_id',
        'doctor_id',
        'appointment_id',
        'order_date',
        'scheduled_at',
        'clinical_history',
        'clinical_indication',
        'priority',
        'status',
        'total_amount',
        'discount',
        'net_amount',
        'paid_amount',
        'payment_status',
        'report_delivered',
        'report_delivered_at',
        'delivered_to',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'scheduled_at' => 'datetime',
        'report_delivered_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'report_delivered' => 'boolean',
    ];

    // ─────────────────────────────────────────────────
    //  RELATIONSHIPS
    // ─────────────────────────────────────────────────

    /**
     * Get the patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the ordering doctor
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the associated appointment
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get all order items
     */
    public function items(): HasMany
    {
        return $this->hasMany(RadiologyOrderItem::class);
    }

    /**
     * Get consent forms
     */
    public function consents(): HasMany
    {
        return $this->hasMany(RadiologyConsent::class);
    }

    // ─────────────────────────────────────────────────
    //  SCOPES
    // ─────────────────────────────────────────────────

    /**
     * Scope: Search by order number, patient name, MRN
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('order_number', 'like', "%{$search}%")
                ->orWhereHas('patient', function ($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%")
                        ->orWhere('mrn', 'like', "%{$search}%")
                        ->orWhere('cnic', 'like', "%{$search}%");
                });
        });
    }

    /**
     * Scope: Today's orders
     */
    public function scopeToday($query)
    {
        return $query->whereDate('order_date', Carbon::today());
    }

    /**
     * Scope: This week's orders
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('order_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    /**
     * Scope: This month's orders
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('order_date', Carbon::now()->month)
            ->whereYear('order_date', Carbon::now()->year);
    }

    /**
     * Scope: Orders with critical findings
     */
    public function scopeWithCritical($query)
    {
        return $query->whereHas('items.report', function ($q) {
            $q->where('is_critical', true);
        });
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope: Filter by patient
     */
    public function scopeByPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope: Pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope: Scheduled orders
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'Scheduled');
    }

    /**
     * Scope: In Progress orders
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'In Progress');
    }

    /**
     * Scope: Completed orders
     */
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['Scan Completed', 'Reporting', 'Reported', 'Verified', 'Delivered']);
    }

    /**
     * Scope: STAT (emergency) orders
     */
    public function scopeStat($query)
    {
        return $query->where('priority', 'STAT');
    }

    /**
     * Scope: Urgent orders
     */
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'Urgent');
    }

    /**
     * Scope: Unpaid orders
     */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('payment_status', ['Unpaid', 'Partial']);
    }

    /**
     * Scope: Fully paid orders
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'Paid');
    }

    /**
     * Scope: Delivered reports
     */
    public function scopeReportDelivered($query)
    {
        return $query->where('report_delivered', true);
    }

    /**
     * Scope: Pending report delivery
     */
    public function scopeReportPending($query)
    {
        return $query->where('report_delivered', false)
            ->whereIn('status', ['Reported', 'Verified']);
    }

    // ─────────────────────────────────────────────────
    //  ACCESSORS & HELPERS
    // ─────────────────────────────────────────────────

    /**
     * Get balance due
     */
    public function getBalanceDueAttribute(): float
    {
        return $this->net_amount - $this->paid_amount;
    }

    /**
     * Check if fully paid
     */
    public function isFullyPaid(): bool
    {
        return $this->payment_status === 'Paid';
    }

    /**
     * Check if order can be cancelled
     */
    public function isCancellable(): bool
    {
        return in_array($this->status, ['Pending', 'Scheduled']);
    }

    /**
     * Get formatted amounts
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'PKR ' . number_format($this->total_amount, 2);
    }

    public function getFormattedNetAttribute(): string
    {
        return 'PKR ' . number_format($this->net_amount, 2);
    }

    public function getFormattedBalanceAttribute(): string
    {
        return 'PKR ' . number_format($this->balance_due, 2);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Pending' => 'warning',
            'Scheduled' => 'info',
            'In Progress' => 'primary',
            'Scan Completed' => 'success',
            'Reporting' => 'info',
            'Reported' => 'success',
            'Verified' => 'success',
            'Delivered' => 'dark',
            'Cancelled' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get priority badge color
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'STAT' => 'danger',
            'Urgent' => 'warning',
            'Routine' => 'secondary',
            default => 'secondary',
        };
    }

    // ─────────────────────────────────────────────────
    //  BUSINESS LOGIC METHODS
    // ─────────────────────────────────────────────────

    /**
     * Sync payment status based on amounts
     */
    public function syncPaymentStatus(): void
    {
        // Recalculate totals from items
        $this->total_amount = $this->items->sum('price');
        $this->net_amount = $this->total_amount - $this->discount;

        // Set final_price for each item
        foreach ($this->items as $item) {
            $item->final_price = $item->price - $item->discount;
            $item->saveQuietly();
        }

        // Determine payment status
        if ($this->paid_amount == 0) {
            $this->payment_status = 'Unpaid';
        } elseif ($this->paid_amount >= $this->net_amount) {
            $this->payment_status = 'Paid';
            $this->paid_amount = $this->net_amount; // Cap at net amount
        } else {
            $this->payment_status = 'Partial';
        }

        $this->saveQuietly();
    }

    // ─────────────────────────────────────────────────
    //  BOOT METHOD
    // ─────────────────────────────────────────────────

    /**
     * Auto-generate order number and set defaults
     */

    public function hasCriticalFindings(): bool
    {
        return $this->items()
            ->whereHas('report', function ($q) {
                $q->where('is_critical', true);
            })
            ->exists();
    }

    


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'RAD-' . str_pad(self::withTrashed()->max('id') + 1, 5, '0', STR_PAD_LEFT);
            }
            if (empty($order->order_date)) {
                $order->order_date = now();
            }
        });

        // Auto-sync payment status after items are updated
        static::saved(function ($order) {
            if ($order->wasChanged(['discount', 'paid_amount'])) {
                $order->syncPaymentStatus();
            }
        });
    }
}