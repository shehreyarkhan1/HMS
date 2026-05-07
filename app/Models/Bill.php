<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bill_number', 'patient_id', 'created_by', 'discount_by',
        'bill_date', 'bill_type', 'status',
        'subtotal', 'discount_amount', 'discount_reason', 'tax_amount',
        'net_amount', 'paid_amount', 'due_amount', 'payment_status',
        'notes', 'finalized_at', 'cancelled_at', 'cancellation_reason',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'finalized_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function discountBy()
    {
        return $this->belongsTo(User::class, 'discount_by');
    }

    public function items()
    {
        return $this->hasMany(BillItem::class);
    }

    public function payments()
    {
        return $this->hasMany(BillPayment::class)->orderBy('payment_date');
    }

    // ─── Scopes ───────────────────────────────────────────────────────
    public function scopeDraft($q)
    {
        return $q->where('status', 'Draft');
    }

    public function scopeFinalized($q)
    {
        return $q->where('status', 'Finalized');
    }

    public function scopeUnpaid($q)
    {
        return $q->where('payment_status', 'Unpaid');
    }

    // ─── Helpers ──────────────────────────────────────────────────────
    public function isDraft(): bool
    {
        return $this->status === 'Draft';
    }

    public function isFinalized(): bool
    {
        return $this->status === 'Finalized';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'Cancelled';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'Paid';
    }

    // ─── Recalculate totals ───────────────────────────────────────────
    public function recalculateTotals(): void
    {
        $subtotal = $this->items()->sum('total_price');
        $netAmount = max(0, $subtotal - $this->discount_amount + $this->tax_amount);
        $dueAmount = max(0, $netAmount - $this->paid_amount);

        $paymentStatus = 'Unpaid';
        if ($this->paid_amount > 0 && $dueAmount <= 0) {
            $paymentStatus = 'Paid';
        } elseif ($this->paid_amount > 0) {
            $paymentStatus = 'Partial';
        }

        $this->update([
            'subtotal' => $subtotal,
            'net_amount' => $netAmount,
            'due_amount' => $dueAmount,
            'payment_status' => $paymentStatus,
        ]);
    }

    // ─── Sync payment status back to source modules ───────────────────
    /**
     * After a payment is recorded on this bill, update the payment_status
     * of linked source records (lab_orders, radiology_orders, dispensings).
     *
     * This keeps the original module's payment_status in sync so that
     * Lab, Radiology, Pharmacy views show correct payment info.
     */
    public function syncSourcePayments(): void
    {
        // Map reference_type → Model class
        $modelMap = [
            'lab_orders' => LabOrder::class,
            'radiology_orders' => RadiologyOrder::class,
            'dispensings' => Dispensing::class,
        ];

        // Get all items that have a reference to a source record
        $linkedItems = $this->items()
            ->whereNotNull('reference_type')
            ->whereNotNull('reference_id')
            ->get(['reference_type', 'reference_id']);

        // Group by reference_type → reference_id (deduplicate)
        $grouped = $linkedItems->groupBy('reference_type');

        foreach ($grouped as $refType => $items) {
            if (! isset($modelMap[$refType])) {
                continue;
            }

            $modelClass = $modelMap[$refType];
            $ids = $items->pluck('reference_id')->unique();

            foreach ($ids as $refId) {
                $record = $modelClass::find($refId);
                if (! $record) {
                    continue;
                }

                // Update payment_status to match this bill's payment_status
                $updateData = ['payment_status' => $this->payment_status];

                // If fully paid, mark paid_amount = net/total amount
                if ($this->payment_status === 'Paid') {
                    $netCol = match ($refType) {
                        'lab_orders' => 'total_amount',
                        'radiology_orders' => 'net_amount',
                        'dispensings' => 'total_amount',
                        default => null,
                    };
                    if ($netCol && isset($record->$netCol)) {
                        $updateData['paid_amount'] = $record->$netCol;
                    }
                }

                $record->update($updateData);
            }
        }
    }

    // ─── Generate bill number ─────────────────────────────────────────
    public static function generateBillNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->withTrashed()->count() + 1;

        return 'BILL-'.$year.'-'.str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public static function billTypes(): array
    {
        return ['OPD', 'IPD', 'Emergency'];
    }

    public static function statusList(): array
    {
        return ['Draft', 'Finalized', 'Cancelled'];
    }

    public static function paymentStatuses(): array
    {
        return ['Unpaid', 'Partial', 'Paid'];
    }
}
