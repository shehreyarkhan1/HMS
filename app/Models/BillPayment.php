<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillPayment extends Model
{
    protected $fillable = [
        'payment_number', 'bill_id', 'received_by',
        'amount', 'payment_method', 'payment_date',
        'reference_number', 'notes',
    ];
 
    protected $casts = [
        'amount'       => 'decimal:2',
        'payment_date' => 'date',
    ];
 
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
 
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
 
    public static function generatePaymentNumber(): string
    {
        $year  = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'PAY-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
 
    public static function paymentMethods(): array
    {
        return ['Cash', 'Card', 'Bank Transfer', 'Cheque', 'Insurance', 'Online'];
    }
 
    public function methodBadgeClass(): string
    {
        return match($this->payment_method) {
            'Cash'          => 'bg-success',
            'Card'          => 'bg-primary',
            'Bank Transfer' => 'bg-info text-dark',
            'Cheque'        => 'bg-warning text-dark',
            'Insurance'     => 'bg-secondary',
            'Online'        => 'bg-purple',
            default         => 'bg-dark',
        };
    }
}
