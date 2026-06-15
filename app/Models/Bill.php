<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use SoftDeletes, HasAuditLog, HasFactory;
    protected string $auditModule = 'Bill'; // For audit log module name

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

    // ─── Status Helpers ───────────────────────────────────────────────

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

    // ─── Recalculate Totals ───────────────────────────────────────────

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

    // ─── Sync Payment Status Back to Source Modules ───────────────────
    /**
     * Called after every payment is recorded on this bill.
     *
     * Updates the payment_status (and related fields) of every linked
     * source record so that Lab, Radiology, Pharmacy, Blood Bank,
     * Mortuary, and Appointment views always show the correct state.
     *
     * Modules covered:
     *   1. lab_orders        → payment_status, paid_amount
     *   2. radiology_orders  → payment_status, paid_amount
     *   3. dispensings       → payment_status, paid_amount
     *   4. death_certificates→ fee_paid, bill_id
     *   5. mortuary_records  → billing_status
     *   6. blood_requests    → payment_status
     *   7. appointments      → payment_status
     *   8. beds              → billing_status (IPD bed charges)
     */
    public function syncSourcePayments(): void
    {
        $isPaid = $this->payment_status === 'Paid';
        $isPartial = $this->payment_status === 'Partial';

        // Load all linked items once — avoid N+1
        $linkedItems = $this->items()
            ->whereNotNull('reference_type')
            ->whereNotNull('reference_id')
            ->get(['reference_type', 'reference_id']);

        if ($linkedItems->isEmpty()) {
            return;
        }

        $grouped = $linkedItems->groupBy('reference_type');

        foreach ($grouped as $refType => $items) {

            $ids = $items->pluck('reference_id')->unique()->filter()->values();

            switch ($refType) {

                // ── 1. Lab Orders ─────────────────────────────────────
                case 'lab_orders':
                    foreach ($ids as $id) {
                        $record = LabOrder::find($id);
                        if (! $record) {
                            continue;
                        }

                        $record->update([
                            'payment_status' => $this->payment_status,
                            'paid_amount' => $isPaid
                                ? ($record->total_amount ?? 0)
                                : $record->paid_amount,
                        ]);
                    }
                    break;

                    // ── 2. Radiology Orders ───────────────────────────────
                case 'radiology_orders':
                    foreach ($ids as $id) {
                        $record = RadiologyOrder::find($id);
                        if (! $record) {
                            continue;
                        }

                        $record->update([
                            'payment_status' => $this->payment_status,
                            'paid_amount' => $isPaid
                                ? ($record->net_amount ?? 0)
                                : $record->paid_amount,
                        ]);
                    }
                    break;

                    // ── 3. Pharmacy Dispensings ───────────────────────────
                case 'dispensings':
                    foreach ($ids as $id) {
                        $record = Dispensing::find($id);
                        if (! $record) {
                            continue;
                        }

                        $record->update([
                            'payment_status' => $this->payment_status,
                            'paid_amount' => $isPaid
                                ? ($record->total_amount ?? 0)
                                : $record->paid_amount,
                        ]);
                    }
                    break;

                    // ── 4. Death Certificates ─────────────────────────────
                    // fee_paid sirf tab true hogi jab bill fully Paid ho.
                    // bill_id hamesha link karo (even on partial) for traceability.
                case 'death_certificates':
                    foreach ($ids as $id) {
                        $record = DeathCertificate::find($id);
                        if (! $record) {
                            continue;
                        }

                        $record->update([
                            'bill_id' => $this->id,
                            'fee_paid' => $isPaid,
                        ]);
                    }
                    break;

                    // ── 5. Mortuary Records ───────────────────────────────
                    // Body storage charges — track karo ke bill ho gaya
                case 'mortuary_records':
                    foreach ($ids as $id) {
                        $record = MortuaryRecord::find($id);
                        if (! $record) {
                            continue;
                        }

                        $record->update([
                            'billing_status' => $isPaid ? 'Paid' : ($isPartial ? 'Partial' : 'Billed'),
                        ]);
                    }
                    break;

                    // ── 6. Blood Requests ─────────────────────────────────
                case 'blood_requests':
                    foreach ($ids as $id) {
                        $record = BloodRequest::find($id);
                        if (! $record) {
                            continue;
                        }

                        $record->update([
                            'payment_status' => $this->payment_status,
                        ]);
                    }
                    break;

                    // ── 7. Appointments (Consultation Fee) ───────────────
                case 'appointments':
                    foreach ($ids as $id) {
                        $record = Appointment::find($id);
                        if (! $record) {
                            continue;
                        }

                        $record->update([
                            'payment_status' => $this->payment_status,
                        ]);
                    }
                    break;

                    // ── 8. Beds (IPD Bed Charges) ─────────────────────────
                    // billing_status track karo — bed release logic alag hai
                case 'beds':
                    foreach ($ids as $id) {
                        $record = Bed::find($id);
                        if (! $record) {
                            continue;
                        }

                        $record->update([
                            'billing_status' => $isPaid ? 'Paid' : ($isPartial ? 'Partial' : 'Billed'),
                        ]);
                    }
                    break;

                    // ── 9. OT Schedules ───────────────────────────────────
                case 'ot_schedules':
                    foreach ($ids as $id) {
                        $record = OtSchedule::find($id);
                        if (! $record) {
                            continue;
                        }
                        $record->update([
                            'billing_status' => $isPaid ? 'Paid' : ($isPartial ? 'Partial' : 'Billed'),
                        ]);
                    }
                    break;

                    // ── Unknown reference types — silently skip ───────────
                default:
                    break;
            }
        }
    }

    // ─── Static Helpers ───────────────────────────────────────────────

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
