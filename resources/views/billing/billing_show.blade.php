@extends('layouts.master')

@section('title', 'Bill #' . $bill->bill_number)
@section('page-title', 'Billing — ' . $bill->bill_number)
@section('breadcrumb', 'Home / Billing / ' . $bill->bill_number)

@push('styles')
<style>
    .card-section { background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden }
    .card-section-header { padding:14px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px }
    .table-clean th { font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0 !important;padding:10px 16px !important;background:#f8fafc }
    .table-clean td { font-size:13px;color:#374151;padding:12px 16px !important;vertical-align:middle;border-bottom:1px solid #f1f5f9 !important }
    .table-clean tr:last-child td { border-bottom:none !important }
    .info-label { font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px }
    .info-value { font-size:13px;font-weight:600;color:#1e293b }
    .summary-box { background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px }
    .summary-row { display:flex;justify-content:space-between;font-size:13px;margin-bottom:10px }
    .summary-total { display:flex;justify-content:space-between;font-size:17px;font-weight:700;color:#1e293b;border-top:1px solid #e2e8f0;padding-top:12px;margin-top:4px }
    .badge-Draft      { background:#f1f5f9;color:#475569;font-size:11px;padding:4px 12px;border-radius:20px;font-weight:500 }
    .badge-Finalized  { background:#dcfce7;color:#166534;font-size:11px;padding:4px 12px;border-radius:20px;font-weight:500 }
    .badge-Cancelled  { background:#fee2e2;color:#991b1b;font-size:11px;padding:4px 12px;border-radius:20px;font-weight:500 }
    .pay-Unpaid  { background:#fee2e2;color:#991b1b;font-size:11px;padding:4px 12px;border-radius:20px;font-weight:500 }
    .pay-Partial { background:#dbeafe;color:#1e40af;font-size:11px;padding:4px 12px;border-radius:20px;font-weight:500 }
    .pay-Paid    { background:#dcfce7;color:#166534;font-size:11px;padding:4px 12px;border-radius:20px;font-weight:500 }
    .svc-Consultation { background:#ede9fe;color:#6d28d9 }
    .svc-Lab          { background:#e0f2fe;color:#0369a1 }
    .svc-Radiology    { background:#fef3c7;color:#92400e }
    .svc-Pharmacy     { background:#dcfce7;color:#166534 }
    .svc-Bed-Charges  { background:#f1f5f9;color:#475569 }
    .svc-OT-Charges   { background:#fee2e2;color:#991b1b }
    .svc-Blood-Bank   { background:#ffe4e6;color:#be123c }
    .svc-Service, .svc-Other { background:#f8fafc;color:#64748b }
    .svc-badge { font-size:10px;padding:2px 8px;border-radius:20px;font-weight:500 }
    .method-Cash          { background:#dcfce7;color:#166534 }
    .method-Card          { background:#dbeafe;color:#1e40af }
    .method-Bank-Transfer { background:#e0f2fe;color:#0369a1 }
    .method-Cheque        { background:#fef3c7;color:#92400e }
    .method-Insurance     { background:#f3e8ff;color:#7e22ce }
    .method-Online        { background:#ede9fe;color:#6d28d9 }
    .method-badge { font-size:10px;padding:2px 8px;border-radius:20px;font-weight:500 }
    .form-control-clean, .form-select-clean {
        border:1px solid #e2e8f0;border-radius:8px;font-size:13px;color:#374151;
        background:#f8fafc;padding:8px 12px;width:100%;outline:none
    }
    .form-control-clean:focus, .form-select-clean:focus { border-color:#93c5fd;background:#fff }
    .form-label-sm { font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px }
</style>
@endpush

@section('content')

{{-- Top Actions --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <a href="{{ route('billing.index') }}" style="font-size:13px;color:#64748b;text-decoration:none">
        <i class="bi bi-arrow-left me-1"></i>Back to Bills
    </a>
    <div class="d-flex gap-2 flex-wrap">
        @if($bill->isDraft())
            <a href="{{ route('billing.edit', $bill) }}" class="btn btn-sm btn-outline-warning" style="font-size:13px">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <form action="{{ route('billing.finalize', $bill) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-success" style="font-size:13px"
                    onclick="return confirm('Finalize this bill? It will be locked for editing.')">
                    <i class="bi bi-check-circle me-1"></i>Finalize Bill
                </button>
            </form>
        @endif
        @if($bill->isFinalized() && !$bill->isPaid())
            <button class="btn btn-sm btn-warning fw-semibold" style="font-size:13px"
                data-bs-toggle="modal" data-bs-target="#paymentModal">
                <i class="bi bi-cash-coin me-1"></i>Record Payment
            </button>
        @endif
        <a href="{{ route('billing.print', $bill) }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="font-size:13px">
            <i class="bi bi-printer me-1"></i>Print Invoice
        </a>
        @if(!$bill->isPaid() && !$bill->isCancelled())
            <button class="btn btn-sm btn-outline-danger" style="font-size:13px"
                data-bs-toggle="modal" data-bs-target="#cancelModal">
                <i class="bi bi-x-circle me-1"></i>Cancel
            </button>
        @endif
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="alert d-flex align-items-center gap-2 mb-4"
    style="background:#dcfce7;border:1px solid #86efac;border-radius:10px;color:#166534;font-size:13px">
    <i class="bi bi-check-circle-fill fs-5"></i>{{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert d-flex align-items-center gap-2 mb-4"
    style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;font-size:13px">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>{{ session('error') }}
</div>
@endif

<div class="row g-4">

    {{-- LEFT --}}
    <div class="col-lg-8">

        {{-- Patient Info --}}
        <div class="card-section mb-4">
            <div class="card-section-header">
                <span style="font-size:14px;font-weight:600;color:#1e293b">
                    <i class="bi bi-person-badge me-2 text-primary"></i>Patient & Bill Details
                </span>
                <div class="d-flex gap-2 align-items-center">
                    <span class="badge-{{ $bill->status }}">{{ $bill->status }}</span>
                    <span class="pay-{{ $bill->payment_status }}">{{ $bill->payment_status }}</span>
                </div>
            </div>
            <div class="p-4">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="info-label">Patient</div>
                        <div class="info-value">{{ $bill->patient?->name }}</div>
                        <div style="font-size:12px;color:#94a3b8">MRN: {{ $bill->patient?->mrn }}</div>
                        <div style="font-size:12px;color:#94a3b8">{{ $bill->patient?->phone }}</div>
                    </div>
                    <div class="col-md-2">
                        <div class="info-label">Bill #</div>
                        <div class="info-value" style="font-family:monospace;color:#6366f1">{{ $bill->bill_number }}</div>
                    </div>
                    <div class="col-md-2">
                        <div class="info-label">Bill Date</div>
                        <div class="info-value">{{ $bill->bill_date->format('d M Y') }}</div>
                    </div>
                    <div class="col-md-2">
                        <div class="info-label">Type</div>
                        <div class="info-value">{{ $bill->bill_type }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-label">Created By</div>
                        <div class="info-value">{{ $bill->createdBy?->name ?? 'System' }}</div>
                        @if($bill->finalized_at)
                        <div style="font-size:11px;color:#94a3b8">Finalized: {{ $bill->finalized_at->format('d M Y H:i') }}</div>
                        @endif
                    </div>
                </div>
                @if($bill->notes)
                <div style="margin-top:14px;padding:10px 14px;background:#f8fafc;border-radius:8px;font-size:12px;color:#64748b">
                    <i class="bi bi-chat-left-text me-1"></i>{{ $bill->notes }}
                </div>
                @endif
            </div>
        </div>

        {{-- Items --}}
        <div class="card-section mb-4">
            <div class="card-section-header">
                <span style="font-size:14px;font-weight:600;color:#1e293b">
                    <i class="bi bi-list-ul me-2 text-success"></i>Bill Items
                </span>
                <span style="font-size:12px;color:#94a3b8">{{ $bill->items->count() }} item(s)</span>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th style="text-align:center">Qty</th>
                            <th style="text-align:right">Unit Price</th>
                            <th style="text-align:right">Discount</th>
                            <th style="text-align:right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bill->items as $i => $item)
                        @php $cls = 'svc-' . str_replace(' ','-',$item->service_type); @endphp
                        <tr>
                            <td style="color:#94a3b8;font-size:12px">{{ $i+1 }}</td>
                            <td><span class="svc-badge {{ $cls }}">{{ $item->service_type }}</span></td>
                            <td style="font-weight:500;color:#1e293b">{{ $item->description }}</td>
                            <td style="text-align:center;color:#64748b">{{ number_format($item->quantity,0) }}</td>
                            <td style="text-align:right;color:#64748b">Rs. {{ number_format($item->unit_price,2) }}</td>
                            <td style="text-align:right;color:#dc2626;font-size:12px">
                                {{ $item->discount > 0 ? '− Rs. '.number_format($item->discount,2) : '—' }}
                            </td>
                            <td style="text-align:right;font-weight:600;color:#1e293b">Rs. {{ number_format($item->total_price,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" style="text-align:right;font-size:12px;color:#64748b;padding:10px 16px !important;border-top:1px solid #e2e8f0 !important;border-bottom:none !important">Subtotal</td>
                            <td style="text-align:right;font-weight:600;color:#1e293b;padding:10px 16px !important;border-top:1px solid #e2e8f0 !important;border-bottom:none !important">Rs. {{ number_format($bill->subtotal,2) }}</td>
                        </tr>
                        @if($bill->discount_amount > 0)
                        <tr>
                            <td colspan="6" style="text-align:right;font-size:12px;color:#dc2626;padding:6px 16px !important;border-bottom:none !important">
                                Discount {{ $bill->discount_reason ? '('.$bill->discount_reason.')' : '' }}
                            </td>
                            <td style="text-align:right;color:#dc2626;font-weight:500;padding:6px 16px !important;border-bottom:none !important">− Rs. {{ number_format($bill->discount_amount,2) }}</td>
                        </tr>
                        @endif
                        @if($bill->tax_amount > 0)
                        <tr>
                            <td colspan="6" style="text-align:right;font-size:12px;color:#0891b2;padding:6px 16px !important;border-bottom:none !important">Tax / Extra Charges</td>
                            <td style="text-align:right;color:#0891b2;font-weight:500;padding:6px 16px !important;border-bottom:none !important">+ Rs. {{ number_format($bill->tax_amount,2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="6" style="text-align:right;font-size:14px;font-weight:700;color:#1e293b;padding:12px 16px !important;border-top:1px solid #e2e8f0 !important;border-bottom:none !important">Net Payable</td>
                            <td style="text-align:right;font-size:15px;font-weight:700;color:#d97706;padding:12px 16px !important;border-top:1px solid #e2e8f0 !important;border-bottom:none !important">Rs. {{ number_format($bill->net_amount,2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Payments --}}
        @if($bill->payments->count() > 0)
        <div class="card-section" id="payment">
            <div class="card-section-header">
                <span style="font-size:14px;font-weight:600;color:#1e293b">
                    <i class="bi bi-cash-coin me-2 text-success"></i>Payment History
                </span>
                <span style="font-size:12px;color:#94a3b8">{{ $bill->payments->count() }} payment(s)</span>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Payment #</th>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Received By</th>
                            <th style="text-align:right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bill->payments as $pay)
                        @php $mc = 'method-' . str_replace(' ','-',$pay->payment_method); @endphp
                        <tr>
                            <td style="font-family:monospace;font-size:12px;color:#6366f1;font-weight:600">{{ $pay->payment_number }}</td>
                            <td style="color:#64748b;font-size:12px">{{ $pay->payment_date->format('d M Y') }}</td>
                            <td><span class="method-badge {{ $mc }}">{{ $pay->payment_method }}</span></td>
                            <td style="color:#64748b;font-size:12px">{{ $pay->reference_number ?? '—' }}</td>
                            <td style="color:#64748b;font-size:12px">{{ $pay->receivedBy?->name ?? '—' }}</td>
                            <td style="text-align:right;font-weight:600;color:#16a34a">Rs. {{ number_format($pay->amount,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>

    {{-- RIGHT --}}
    <div class="col-lg-4">
        <div class="summary-box" style="position:sticky;top:80px">
            <div style="font-size:13px;font-weight:700;color:#1e293b;margin-bottom:16px">
                <i class="bi bi-calculator me-2 text-warning"></i>Financial Summary
            </div>
            <div class="summary-row"><span style="color:#64748b">Subtotal</span><span style="font-weight:500">Rs. {{ number_format($bill->subtotal,2) }}</span></div>
            @if($bill->discount_amount > 0)
            <div class="summary-row"><span style="color:#dc2626">Discount</span><span style="color:#dc2626">− Rs. {{ number_format($bill->discount_amount,2) }}</span></div>
            @endif
            @if($bill->tax_amount > 0)
            <div class="summary-row"><span style="color:#0891b2">Tax / Extra</span><span style="color:#0891b2">+ Rs. {{ number_format($bill->tax_amount,2) }}</span></div>
            @endif
            <div class="summary-total"><span>Net Payable</span><span style="color:#d97706">Rs. {{ number_format($bill->net_amount,2) }}</span></div>
            <div class="summary-row mt-3"><span style="color:#16a34a;font-weight:500">Paid Amount</span><span style="color:#16a34a;font-weight:600">Rs. {{ number_format($bill->paid_amount,2) }}</span></div>
            <div class="summary-row">
                <span style="color:{{ $bill->due_amount > 0 ? '#dc2626' : '#94a3b8' }};font-weight:500">Due Amount</span>
                <span style="color:{{ $bill->due_amount > 0 ? '#dc2626' : '#94a3b8' }};font-weight:600">Rs. {{ number_format($bill->due_amount,2) }}</span>
            </div>

            @if($bill->net_amount > 0)
            @php $pct = min(100, round(($bill->paid_amount / $bill->net_amount) * 100)); @endphp
            <div style="margin-top:8px;margin-bottom:16px">
                <div style="display:flex;justify-content:space-between;font-size:11px;color:#94a3b8;margin-bottom:4px">
                    <span>Payment Progress</span><span>{{ $pct }}%</span>
                </div>
                <div style="background:#e2e8f0;border-radius:99px;height:6px">
                    <div style="width:{{ $pct }}%;height:6px;border-radius:99px;background:{{ $pct>=100 ? '#16a34a' : ($pct>0 ? '#d97706' : '#dc2626') }}"></div>
                </div>
            </div>
            @endif

            @if($bill->isFinalized() && !$bill->isPaid())
            <button class="btn btn-warning w-100 fw-semibold" style="font-size:13px"
                data-bs-toggle="modal" data-bs-target="#paymentModal">
                <i class="bi bi-cash-coin me-2"></i>Record Payment
            </button>
            @endif
            @if($bill->isCancelled())
            <div style="text-align:center;font-size:12px;color:#dc2626;padding:8px;background:#fef2f2;border-radius:8px">
                <i class="bi bi-x-circle me-1"></i>Bill Cancelled
                @if($bill->cancellation_reason)
                <br><span style="color:#94a3b8">{{ $bill->cancellation_reason }}</span>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Payment Modal --}}
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom" style="border-color:#e2e8f0 !important">
                <h5 class="modal-title" style="font-size:15px;font-weight:600;color:#1e293b">
                    <i class="bi bi-cash-coin me-2 text-warning"></i>Record Payment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('billing.payment', $bill) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div style="background:#fefce8;border:1px solid #fde047;border-radius:8px;padding:10px 14px;font-size:12px;color:#713f12;margin-bottom:16px">
                        <i class="bi bi-info-circle me-1"></i>
                        Outstanding Due: <strong>Rs. {{ number_format($bill->due_amount,2) }}</strong>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="form-label-sm">Amount <span style="color:#dc2626">*</span></div>
                            <input type="number" name="amount" class="form-control-clean"
                                value="{{ $bill->due_amount }}" min="0.01" step="0.01"
                                max="{{ $bill->due_amount }}" required>
                        </div>
                        <div class="col-6">
                            <div class="form-label-sm">Payment Date <span style="color:#dc2626">*</span></div>
                            <input type="date" name="payment_date" class="form-control-clean"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-6">
                            <div class="form-label-sm">Method <span style="color:#dc2626">*</span></div>
                            <select name="payment_method" class="form-select-clean" required>
                                @foreach(\App\Models\BillPayment::paymentMethods() as $m)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <div class="form-label-sm">Reference # (optional)</div>
                            <input type="text" name="reference_number" class="form-control-clean" placeholder="TXN / Cheque no...">
                        </div>
                        <div class="col-12">
                            <div class="form-label-sm">Notes</div>
                            <input type="text" name="notes" class="form-control-clean" placeholder="Optional note...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-color:#e2e8f0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-semibold px-4">
                        <i class="bi bi-check-circle me-1"></i>Confirm Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Cancel Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="border-color:#e2e8f0">
                <h5 class="modal-title" style="font-size:15px;font-weight:600;color:#dc2626">
                    <i class="bi bi-x-circle me-2"></i>Cancel Bill
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('billing.cancel', $bill) }}" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:10px 14px;font-size:12px;color:#991b1b;margin-bottom:14px">
                        <i class="bi bi-exclamation-triangle me-1"></i>This cannot be undone. Please provide a reason.
                    </div>
                    <div class="form-label-sm">Cancellation Reason <span style="color:#dc2626">*</span></div>
                    <textarea name="cancellation_reason" class="form-control-clean" rows="3"
                        placeholder="Why is this bill being cancelled?" required></textarea>
                </div>
                <div class="modal-footer" style="border-color:#e2e8f0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Go Back</button>
                    <button type="submit" class="btn btn-danger btn-sm px-4">Cancel Bill</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
