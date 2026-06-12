@php
    $cur = $layoutsSetting['currency_symbol'] ?? '₨';
    $hospitalName = $layoutsSetting['hospital_name'] ?? 'Medicare Hospital';
    $hospitalAddr = $layoutsSetting['hospital_address'] ?? 'Peshawar, KPK';
    $hospitalPhone = $layoutsSetting['hospital_phone'] ?? '';
    $hospitalEmail = $layoutsSetting['hospital_email'] ?? '';
    $footerNote = $layoutsSetting['bill_footer_note'] ?? 'Thank you for choosing our hospital.';
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice — {{ $bill->bill_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 13px;
            color: #1e293b;
            background: #fff
        }

        .page {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px
        }

        /* Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 36px;
            padding-bottom: 24px;
            border-bottom: 2px solid #1e293b
        }

        .hospital-name {
            font-size: 22px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 4px
        }

        .hospital-sub {
            font-size: 11px;
            color: #64748b;
            line-height: 1.6
        }

        .invoice-meta {
            text-align: right
        }

        .invoice-title {
            font-size: 26px;
            font-weight: 700;
            color: #d97706;
            letter-spacing: .02em
        }

        .invoice-num {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px
        }

        .invoice-num span {
            font-weight: 600;
            color: #1e293b;
            font-family: monospace
        }

        /* Status */
        .status-banner {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 6px
        }

        .status-Draft {
            background: #f1f5f9;
            color: #475569
        }

        .status-Finalized {
            background: #dcfce7;
            color: #166534
        }

        .status-Cancelled {
            background: #fee2e2;
            color: #991b1b
        }

        .pay-Paid {
            background: #dcfce7;
            color: #166534
        }

        .pay-Partial {
            background: #dbeafe;
            color: #1e40af
        }

        .pay-Unpaid {
            background: #fee2e2;
            color: #991b1b
        }

        /* Patient / Bill Info */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 28px
        }

        .info-box {
            background: #f8fafc;
            border-radius: 10px;
            padding: 14px 18px
        }

        .info-box-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #94a3b8;
            margin-bottom: 10px
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 5px
        }

        .info-row .label {
            color: #64748b
        }

        .info-row .value {
            font-weight: 600;
            color: #1e293b;
            text-align: right
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0
        }

        .items-table thead th {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
            padding: 9px 12px;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0
        }

        .items-table tbody td {
            font-size: 12px;
            color: #374151;
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9
        }

        .text-right {
            text-align: right
        }

        .text-center {
            text-align: center
        }

        /* Totals */
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 0
        }

        .totals-box {
            width: 280px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 16px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9
        }

        .totals-row.grand {
            background: #1e293b;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            border-bottom: none
        }

        .totals-row.grand .amount {
            color: #fbbf24
        }

        /* Payments */
        .payments-section {
            margin-top: 24px
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #64748b;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e2e8f0
        }

        .pay-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 7px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px
        }

        .pay-badge {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500
        }

        .method-Cash {
            background: #dcfce7;
            color: #166534
        }

        .method-Card {
            background: #dbeafe;
            color: #1e40af
        }

        .method-Cheque {
            background: #fef3c7;
            color: #92400e
        }

        .method-Other {
            background: #f1f5f9;
            color: #475569
        }

        /* Footer */
        .invoice-footer {
            margin-top: 36px;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-end
        }

        .footer-note {
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.7;
            max-width: 50%
        }

        .signature-box {
            text-align: center
        }

        .signature-line {
            width: 160px;
            border-top: 1px solid #94a3b8;
            margin: 0 auto;
            padding-top: 6px;
            font-size: 11px;
            color: #64748b
        }

        /* Cancelled watermark */
        @if ($bill->status === 'Cancelled')
            .page::before {
                content: 'CANCELLED';
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-35deg);
                font-size: 90px;
                font-weight: 900;
                color: rgba(220, 38, 38, .1);
                pointer-events: none;
                z-index: 0;
                white-space: nowrap
            }
        @endif

        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact
            }

            .no-print {
                display: none !important
            }

            .page {
                padding: 20px
            }
        }
    </style>
</head>

<body>

    {{-- Print toolbar --}}
    <div class="no-print"
        style="background:#1e293b;padding:10px 20px;display:flex;justify-content:space-between;align-items:center">
        <span style="color:#fff;font-size:13px"><i>Invoice Preview — {{ $bill->bill_number }}</i></span>
        <div style="display:flex;gap:8px">
            <button onclick="window.print()"
                style="background:#d97706;color:#fff;border:none;border-radius:6px;padding:6px 16px;font-size:13px;cursor:pointer">
                🖨️ Print
            </button>
            <button onclick="window.close()"
                style="background:#475569;color:#fff;border:none;border-radius:6px;padding:6px 16px;font-size:13px;cursor:pointer">
                ✕ Close
            </button>
        </div>
    </div>

    <div class="page">

        {{-- ─ Header ─────────────────────────────────────────── --}}
        <div class="invoice-header">
            <div>
                <div class="hospital-name">{{ $hospitalName }}</div>
                <div class="hospital-sub">
                    {{ $hospitalAddr }}<br>
                    @if ($hospitalPhone)
                        Phone: {{ $hospitalPhone }}
                    @endif
                    @if ($hospitalEmail)
                        &nbsp;|&nbsp; Email: {{ $hospitalEmail }}
                    @endif
                </div>
            </div>
            <div class="invoice-meta">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-num">Bill No: <span>{{ $bill->bill_number }}</span></div>
                <div class="invoice-num">Date: <span>{{ $bill->bill_date->format('d F Y') }}</span></div>
                <div style="margin-top:6px">
                    <span class="status-banner status-{{ $bill->status }}">{{ $bill->status }}</span>
                    <span class="status-banner pay-{{ $bill->payment_status }}"
                        style="margin-left:4px">{{ $bill->payment_status }}</span>
                </div>
            </div>
        </div>

        {{-- ─ Patient & Bill Info ──────────────────────────── --}}
        <div class="info-grid">
            <div class="info-box">
                <div class="info-box-title">Bill To — Patient</div>
                <div class="info-row">
                    <span class="label">Name</span>
                    <span class="value">{{ $bill->patient->name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">MRN</span>
                    <span class="value">{{ $bill->patient->mrn }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Phone</span>
                    <span class="value">{{ $bill->patient->phone }}</span>
                </div>
                @if ($bill->patient->address)
                    <div class="info-row">
                        <span class="label">Address</span>
                        <span class="value" style="max-width:180px;text-align:right">
                            {{ $bill->patient->address }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="info-box">
                <div class="info-box-title">Invoice Details</div>
                <div class="info-row">
                    <span class="label">Bill Type</span>
                    <span class="value">{{ $bill->bill_type }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Bill Date</span>
                    <span class="value">{{ $bill->bill_date->format('d M Y') }}</span>
                </div>
                @if ($bill->finalized_at)
                    <div class="info-row">
                        <span class="label">Finalized</span>
                        <span class="value">{{ $bill->finalized_at->format('d M Y') }}</span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="label">Created By</span>
                    <span class="value">{{ $bill->createdBy?->name ?? 'System' }}</span>
                </div>
            </div>
        </div>

        {{-- ─ Items Table ──────────────────────────────────── --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:30px">#</th>
                    <th>Service Type</th>
                    <th>Description</th>
                    <th class="text-center" style="width:50px">Qty</th>
                    <th class="text-right" style="width:110px">Unit Price</th>
                    <th class="text-right" style="width:90px">Discount</th>
                    <th class="text-right" style="width:110px">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bill->items as $i => $item)
                    <tr>
                        <td style="color:#94a3b8">{{ $i + 1 }}</td>
                        <td style="color:#64748b;font-size:11px">{{ $item->service_type }}</td>
                        <td style="font-weight:500">{{ $item->description }}</td>
                        <td class="text-center">{{ number_format($item->quantity, 0) }}</td>
                        <td class="text-right">{{ $cur }} {{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right" style="color:#dc2626">
                            {{ $item->discount > 0 ? '− ' . number_format($item->discount, 2) : '—' }}
                        </td>
                        <td class="text-right" style="font-weight:600">
                            {{ $cur }} {{ number_format($item->total_price, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ─ Totals ───────────────────────────────────────── --}}
        <div class="totals-section" style="margin-top:16px">
            <div class="totals-box">

                <div class="totals-row">
                    <span style="color:#64748b">Subtotal</span>
                    <span>{{ $cur }} {{ number_format($bill->subtotal, 2) }}</span>
                </div>

                @if ($bill->discount_amount > 0)
                    <div class="totals-row">
                        <span style="color:#dc2626">
                            Discount{{ $bill->discount_reason ? ' (' . $bill->discount_reason . ')' : '' }}
                        </span>
                        <span style="color:#dc2626">− {{ $cur }}
                            {{ number_format($bill->discount_amount, 2) }}</span>
                    </div>
                @endif

                @if ($bill->tax_amount > 0)
                    <div class="totals-row">
                        <span style="color:#0891b2">Tax / Charges</span>
                        <span style="color:#0891b2">+ {{ $cur }}
                            {{ number_format($bill->tax_amount, 2) }}</span>
                    </div>
                @endif

                <div class="totals-row grand">
                    <span>Net Payable</span>
                    <span class="amount">{{ $cur }} {{ number_format($bill->net_amount, 2) }}</span>
                </div>

                @if ($bill->paid_amount > 0)
                    <div class="totals-row" style="background:#f0fdf4">
                        <span style="color:#16a34a">Amount Paid</span>
                        <span style="color:#16a34a;font-weight:600">
                            {{ $cur }} {{ number_format($bill->paid_amount, 2) }}
                        </span>
                    </div>
                @endif

                @if ($bill->due_amount > 0)
                    <div class="totals-row" style="background:#fef2f2">
                        <span style="color:#dc2626;font-weight:600">Balance Due</span>
                        <span style="color:#dc2626;font-weight:700">
                            {{ $cur }} {{ number_format($bill->due_amount, 2) }}
                        </span>
                    </div>
                @endif

            </div>
        </div>

        {{-- ─ Payments ─────────────────────────────────────── --}}
        @if ($bill->payments->count() > 0)
            <div class="payments-section">
                <div class="section-title">Payment History</div>
                @foreach ($bill->payments as $pay)
                    @php
                        $mc =
                            'method-' .
                            (in_array($pay->payment_method, ['Cash', 'Card', 'Cheque'])
                                ? $pay->payment_method
                                : 'Other');
                    @endphp
                    <div class="pay-row">
                        <div>
                            <span class="pay-badge {{ $mc }}">{{ $pay->payment_method }}</span>
                            <span style="color:#64748b;font-size:11px;margin-left:8px">
                                {{ $pay->payment_date->format('d M Y') }}
                            </span>
                            @if ($pay->reference_number)
                                <span style="color:#94a3b8;font-size:11px;margin-left:4px">
                                    Ref: {{ $pay->reference_number }}
                                </span>
                            @endif
                        </div>
                        <span style="font-weight:600;color:#16a34a">
                            {{ $cur }} {{ number_format($pay->amount, 2) }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ─ Notes ────────────────────────────────────────── --}}
        @if ($bill->notes)
            <div
                style="margin-top:20px;background:#f8fafc;border-radius:8px;padding:10px 14px;font-size:12px;color:#64748b">
                <strong>Note:</strong> {{ $bill->notes }}
            </div>
        @endif

        {{-- ─ Footer ───────────────────────────────────────── --}}
        <div class="invoice-footer">
            <div class="footer-note">
                {{ $footerNote }}<br>
                This is a computer-generated invoice and does not require a physical signature.<br>
                @if ($hospitalEmail)
                    {{ $hospitalEmail }}
                @endif
                @if ($hospitalPhone)
                    &nbsp;|&nbsp; {{ $hospitalPhone }}
                @endif
            </div>
            <div class="signature-box">
                <div style="height:40px"></div>
                <div class="signature-line">Authorized Signatory</div>
            </div>
        </div>

    </div>{{-- end .page --}}

</body>

</html>
