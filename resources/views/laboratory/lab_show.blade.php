@extends('layouts.master')

@section('title', 'Lab Order — ' . $labOrder->order_number)
@section('page-title', 'Laboratory — Order Detail')
@section('breadcrumb', 'Home / Laboratory / Orders / ' . $labOrder->order_number)

@push('styles')
    <style>
        .detail-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px
        }

        .detail-card-header {
            padding: 12px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .detail-card-body {
            padding: 20px
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px
        }

        .meta-item-label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 3px
        }

        .meta-item-value {
            font-size: 13px;
            font-weight: 500;
            color: #1e293b
        }

        /* Result table */
        .result-table th {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            padding: 9px 14px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0
        }

        .result-table td {
            font-size: 13px;
            padding: 12px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9
        }

        .result-table tr:last-child td {
            border-bottom: none
        }

        .flag-Normal {
            color: #16a34a;
            background: #dcfce7;
            padding: 2px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600
        }

        .flag-High {
            color: #dc2626;
            background: #fee2e2;
            padding: 2px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600
        }

        .flag-Low {
            color: #1d4ed8;
            background: #dbeafe;
            padding: 2px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600
        }

        .flag-Critical-High {
            color: #fff;
            background: #dc2626;
            padding: 2px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700
        }

        .flag-Critical-Low {
            color: #fff;
            background: #1d4ed8;
            padding: 2px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700
        }

        .result-input {
            height: 32px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            padding: 0 10px;
            background: #f8fafc;
            width: 100%
        }

        .result-input:focus {
            outline: none;
            border-color: #93c5fd;
            background: #fff
        }

        /* Status badges reuse */
        .badge-Pending {
            background: #fef9c3;
            color: #854d0e;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-Sample-Collected {
            background: #e0f2fe;
            color: #0369a1;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-Processing {
            background: #fef3c7;
            color: #92400e;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-Completed {
            background: #dcfce7;
            color: #166534;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-Cancelled {
            background: #fee2e2;
            color: #991b1b;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .priority-STAT {
            background: #fee2e2;
            color: #dc2626;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 700
        }

        .priority-Urgent {
            background: #fef3c7;
            color: #d97706;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600
        }

        .priority-Routine {
            background: #f1f5f9;
            color: #64748b;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500
        }

        .pay-Unpaid {
            background: #fee2e2;
            color: #991b1b;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .pay-Partial {
            background: #dbeafe;
            color: #1e40af;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .pay-Paid {
            background: #dcfce7;
            color: #166534;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('collectBtn');
            const form = document.getElementById('collectForm');
            if (btn && form) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (form.style.display === 'none' || form.style.display === '') {
                        form.style.display = 'block';
                    } else {
                        form.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endpush

@section('content')

    {{-- Session alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" style="border-radius:10px;font-size:13px">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header strip --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('lab.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h5 class="mb-0" style="font-size:16px;font-weight:700;color:#1e293b">
                    {{ $labOrder->order_number }}
                </h5>
                <div style="font-size:12px;color:#94a3b8">
                    {{ $labOrder->order_date->format('d M Y, H:i') }}
                </div>
            </div>
            <span class="badge-{{ str_replace(' ', '-', $labOrder->status) }}">
                {{ $labOrder->status }}
            </span>
            <span class="priority-{{ $labOrder->priority }}">{{ $labOrder->priority }}</span>
        </div>
        <div class="d-flex gap-2">
            @if($labOrder->status === 'Completed' && !$labOrder->report_delivered)
                <form method="POST" action="{{ route('lab.orders.deliverReport', $labOrder->id) }}">
                    @csrf
                    <button class="btn btn-sm btn-success" style="font-size:13px">
                        <i class="bi bi-check2-circle me-1"></i>Mark Report Delivered
                    </button>
                </form>
            @endif
            @if(!in_array($labOrder->status, ['Completed', 'Cancelled']))
                <form method="POST" action="{{ route('lab.orders.cancel', $labOrder->id) }}"
                    onsubmit="return confirm('Cancel this order?')">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger" style="font-size:13px">
                        <i class="bi bi-x-circle me-1"></i>Cancel Order
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row g-4">
        {{-- Left --}}
        <div class="col-lg-8">

            {{-- Order Info --}}
            <div class="detail-card">
                <div class="detail-card-header">
                    <span><i class="bi bi-info-circle me-2 text-primary"></i>Order Information</span>
                </div>
                <div class="detail-card-body">
                    <div class="meta-grid">
                        <div>
                            <div class="meta-item-label">Patient</div>
                            <div class="meta-item-value">{{ $labOrder->patient->name }}</div>
                            <div style="font-size:11px;color:#94a3b8">{{ $labOrder->patient->mrn }}</div>
                        </div>
                        <div>
                            <div class="meta-item-label">Doctor</div>
                            <div class="meta-item-value">{{ $labOrder->doctor->name ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="meta-item-label">Order Date</div>
                            <div class="meta-item-value">{{ $labOrder->order_date->format('d M Y, H:i') }}</div>
                        </div>
                        @if($labOrder->appointment)
                            <div>
                                <div class="meta-item-label">Appointment</div>
                                <div class="meta-item-value"># {{ $labOrder->appointment->id }}</div>
                            </div>
                        @endif
                        @if($labOrder->notes)
                            <div style="grid-column:1/-1">
                                <div class="meta-item-label">Notes</div>
                                <div class="meta-item-value">{{ $labOrder->notes }}</div>
                            </div>
                        @endif
                        @if($labOrder->report_delivered)
                            <div>
                                <div class="meta-item-label">Report Delivered</div>
                                <div class="meta-item-value" style="color:#16a34a">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ $labOrder->report_delivered_at?->format('d M Y, H:i') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Samples Section --}}
            <div class="detail-card" id="collect">
                <div class="detail-card-header">
                    <span><i class="bi bi-droplet-fill me-2" style="color:#0ea5e9"></i>Samples</span>
                    @if($labOrder->status === 'Pending')
                        @if($labOrder->status === 'Pending')
                            <button type="button" id="collectBtn" class="btn btn-sm btn-warning" style="font-size:12px">
                                <i class="bi bi-plus me-1"></i>Collect Sample
                            </button>
                        @endif
                    @endif
                </div>

                {{-- Collect form --}}
                @if($labOrder->status === 'Pending')
                    <div id="collectForm" style="display:none">
                        <div style="padding:16px 20px;background:#fffbeb;border-bottom:1px solid #e2e8f0">
                            <form method="POST" action="{{ route('lab.orders.collectSample', $labOrder->id) }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label
                                            style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:4px">
                                            Sample Type <span class="text-danger">*</span>
                                        </label>
                                        <select name="sample_type_id" required
                                            style="height:34px;border:1px solid #e2e8f0;border-radius:7px;font-size:13px;padding:0 10px;width:100%;background:#fff">
                                            <option value="">Select type...</option>
                                            @foreach(\App\Models\LabSampleType::active()->orderBy('name')->get() as $st)
                                                <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:4px">
                                            Collected By <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="collected_by" required placeholder="Staff name..."
                                            style="height:34px;border:1px solid #e2e8f0;border-radius:7px;font-size:13px;padding:0 10px;width:100%;background:#fff">
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:4px">
                                            Link Tests
                                        </label>
                                        @foreach($labOrder->items->where('status', '!=', 'Cancelled') as $item)
                                            <div class="form-check" style="font-size:12px">
                                                <input class="form-check-input" type="checkbox" name="item_ids[]"
                                                    value="{{ $item->id }}" id="item_{{ $item->id }}" checked>
                                                <label class="form-check-label" for="item_{{ $item->id }}">
                                                    {{ $item->labTest->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-sm btn-warning" style="font-size:13px">
                                        <i class="bi bi-droplet-fill me-1"></i>Confirm Sample Collection
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Samples list --}}
                <div class="detail-card-body"
                    style="{{ $labOrder->samples->isEmpty() ? 'padding:30px;text-align:center;color:#94a3b8;font-size:13px' : '' }}">
                    @if($labOrder->samples->isEmpty())
                        <i class="bi bi-droplet" style="font-size:28px;display:block;margin-bottom:6px"></i>
                        No samples collected yet.
                    @else
                        @foreach($labOrder->samples as $sample)
                            <div style="border:1px solid #e2e8f0;border-radius:8px;padding:12px 16px;margin-bottom:10px">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <code style="font-size:12px;color:#6366f1">{{ $sample->sample_number }}</code>
                                        <span style="font-size:13px;font-weight:500;color:#1e293b;margin-left:8px">
                                            {{ $sample->sampleType->name }}
                                        </span>
                                    </div>
                                    <span
                                        style="font-size:11px;padding:2px 9px;border-radius:20px;font-weight:500;
                                                                            background:{{ $sample->status === 'Collected' ? '#dcfce7' : ($sample->status === 'Rejected' ? '#fee2e2' : '#f1f5f9') }};
                                                                            color:{{ $sample->status === 'Collected' ? '#166534' : ($sample->status === 'Rejected' ? '#991b1b' : '#64748b') }}">
                                        {{ $sample->status }}
                                    </span>
                                </div>
                                <div style="font-size:11px;color:#94a3b8;margin-top:4px">
                                    Collected by {{ $sample->collected_by }}
                                    at {{ $sample->collected_at?->format('d M Y, H:i') }}
                                    @if($sample->rejection_reason)
                                        · <span style="color:#dc2626">Rejected: {{ $sample->rejection_reason }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Results Section --}}
            <div class="detail-card" id="results">
                <div class="detail-card-header">
                    <span><i class="bi bi-clipboard2-pulse me-2 text-success"></i>Test Results</span>
                </div>

                <table class="table result-table mb-0">
                    <thead>
                        <tr>
                            <th>Test</th>
                            <th>Result</th>
                            <th>Unit</th>
                            <th>Normal Range</th>
                            <th>Flag</th>
                            <th>Remarks</th>
                            <th>Verified</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($labOrder->items->where('status', '!=', 'Cancelled') as $item)
                            <tr>
                                <td>
                                    <div style="font-weight:500;color:#1e293b">{{ $item->labTest->name }}</div>
                                    <div style="font-size:11px;color:#94a3b8">{{ $item->labTest->test_code }}</div>
                                </td>
                                <td>
                                    @if($item->result)
                                        <span style="font-size:13px;font-weight:600;color:#1e293b">
                                            {{ $item->result->result_value }}
                                        </span>
                                    @else
                                        <span style="color:#94a3b8">—</span>
                                    @endif
                                </td>
                                <td style="color:#64748b">{{ $item->result?->unit ?? $item->labTest->unit ?? '—' }}</td>
                                <td style="color:#64748b;font-size:12px">
                                    {{ $item->result?->normal_range ?? $item->labTest->normal_range ?? '—' }}
                                </td>
                                <td>
                                    @if($item->result?->flag)
                                        <span class="flag-{{ str_replace(' ', '-', $item->result->flag) }}">
                                            {{ $item->result->flag }}
                                        </span>
                                    @else
                                        <span style="color:#94a3b8">—</span>
                                    @endif
                                </td>
                                <td style="font-size:12px;color:#64748b">
                                    {{ $item->result?->remarks ?? '—' }}
                                </td>
                                <td>
                                    @if($item->result?->is_verified)
                                        <span style="font-size:11px;color:#16a34a">
                                            <i class="bi bi-patch-check-fill me-1"></i>
                                            {{ $item->result->verifiedBy?->name ?? 'Verified' }}
                                        </span>
                                    @elseif($item->result)
                                        <form method="POST" action="{{ route('lab.results.verify', $item->result->id) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success py-0 px-2" style="font-size:11px">
                                                <i class="bi bi-check2"></i> Verify
                                            </button>
                                        </form>
                                    @else
                                        <span style="color:#94a3b8;font-size:11px">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Results entry form --}}
                @if(in_array($labOrder->status, ['Sample Collected', 'Processing']))
                    <div style="padding:16px 20px;background:#f0fdf4;border-top:1px solid #e2e8f0">
                        <div style="font-size:13px;font-weight:600;color:#374151;margin-bottom:12px">
                            <i class="bi bi-pencil-square me-2 text-success"></i>Enter / Update Results
                        </div>
                        <form method="POST" action="{{ route('lab.orders.storeResults', $labOrder->id) }}">
                            @csrf
                            @foreach($labOrder->items->where('status', '!=', 'Cancelled') as $idx => $item)
                                <div
                                    style="border:1px solid #e2e8f0;border-radius:8px;padding:14px;margin-bottom:12px;background:#fff">
                                    <div style="font-size:13px;font-weight:600;color:#1e293b;margin-bottom:10px">
                                        {{ $item->labTest->name }}
                                        <span style="font-size:11px;color:#94a3b8;font-weight:400">
                                            — {{ $item->labTest->test_code }}
                                        </span>
                                    </div>
                                    <input type="hidden" name="results[{{ $idx }}][item_id]" value="{{ $item->id }}">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label style="font-size:11px;color:#64748b;display:block;margin-bottom:3px">
                                                Result Value
                                            </label>
                                            <input type="text" name="results[{{ $idx }}][result_value]" class="result-input"
                                                value="{{ $item->result?->result_value }}" placeholder="e.g. 5.6, Positive">
                                        </div>
                                        <div class="col-md-2">
                                            <label style="font-size:11px;color:#64748b;display:block;margin-bottom:3px">
                                                Unit
                                            </label>
                                            <input type="text" name="results[{{ $idx }}][unit]" class="result-input"
                                                value="{{ $item->result?->unit ?? $item->labTest->unit }}"
                                                placeholder="{{ $item->labTest->unit }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label style="font-size:11px;color:#64748b;display:block;margin-bottom:3px">
                                                Normal Range
                                            </label>
                                            <input type="text" name="results[{{ $idx }}][normal_range]" class="result-input"
                                                value="{{ $item->result?->normal_range ?? $item->labTest->normal_range }}"
                                                placeholder="{{ $item->labTest->normal_range }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label style="font-size:11px;color:#64748b;display:block;margin-bottom:3px">
                                                Remarks
                                            </label>
                                            <input type="text" name="results[{{ $idx }}][remarks]" class="result-input"
                                                value="{{ $item->result?->remarks }}" placeholder="Optional remarks">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <button type="submit" class="btn btn-success" style="font-size:13px;height:38px">
                                <i class="bi bi-save me-2"></i>Save Results
                            </button>
                        </form>
                    </div>
                @endif
            </div>

        </div>

        {{-- Right: Billing --}}
        <div class="col-lg-4">
            <div class="detail-card" style="position:sticky;top:20px">
                <div class="detail-card-header">
                    <span><i class="bi bi-receipt me-2 text-primary"></i>Billing</span>
                    <span class="pay-{{ $labOrder->payment_status }}">{{ $labOrder->payment_status }}</span>
                </div>
                <div class="detail-card-body">
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                        <span style="font-size:13px;color:#64748b">Subtotal</span>
                        <span style="font-size:13px;font-weight:500">Rs
                            {{ number_format($labOrder->total_amount, 0) }}</span>
                    </div>
                    @if($labOrder->discount > 0)
                        <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                            <span style="font-size:13px;color:#64748b">Discount</span>
                            <span style="font-size:13px;color:#dc2626">−Rs {{ number_format($labOrder->discount, 0) }}</span>
                        </div>
                    @endif
                    <div
                        style="display:flex;justify-content:space-between;padding:10px 0;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;margin-bottom:10px">
                        <span style="font-size:14px;font-weight:700;color:#1e293b">Net Amount</span>
                        <span style="font-size:16px;font-weight:700;color:#1e293b">Rs
                            {{ number_format($labOrder->net_amount, 0) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                        <span style="font-size:13px;color:#64748b">Paid</span>
                        <span style="font-size:13px;font-weight:500;color:#16a34a">Rs
                            {{ number_format($labOrder->paid_amount, 0) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:16px">
                        <span style="font-size:13px;color:#64748b">Balance</span>
                        <span
                            style="font-size:13px;font-weight:600;color:{{ $labOrder->balance > 0 ? '#dc2626' : '#16a34a' }}">
                            Rs {{ number_format($labOrder->balance, 0) }}
                        </span>
                    </div>

                    {{-- Payment form --}}
                    @if($labOrder->balance > 0)
                        <form method="POST" action="{{ route('lab.orders.recordPayment', $labOrder->id) }}">
                            @csrf
                            <div style="font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">
                                Record Payment
                            </div>
                            <div class="d-flex gap-2">
                                <input type="number" name="paid_amount"
                                    style="height:34px;border:1px solid #e2e8f0;border-radius:7px;font-size:13px;padding:0 10px;flex:1;background:#f8fafc"
                                    placeholder="Amount Rs" min="1" max="{{ $labOrder->balance }}" required>
                                <button type="submit" class="btn btn-sm btn-success"
                                    style="height:34px;font-size:12px;white-space:nowrap">
                                    <i class="bi bi-cash me-1"></i>Pay
                                </button>
                            </div>
                        </form>
                    @else
                        <div
                            style="text-align:center;padding:10px;background:#f0fdf4;border-radius:8px;font-size:13px;color:#16a34a;font-weight:600">
                            <i class="bi bi-check-circle me-2"></i>Fully Paid
                        </div>
                    @endif

                </div>
            </div>

            {{-- Tests list summary --}}
            <div class="detail-card">
                <div class="detail-card-header">
                    <span><i class="bi bi-list-check me-2 text-primary"></i>Tests</span>
                </div>
                <div style="padding:0">
                    @foreach($labOrder->items as $item)
                        <div
                            style="padding:11px 18px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center">
                            <div>
                                <div style="font-size:13px;font-weight:500;color:#1e293b">
                                    {{ $item->labTest->name }}
                                </div>
                                <div style="font-size:11px;color:#94a3b8">{{ $item->labTest->test_code }}</div>
                            </div>
                            <div style="text-align:right">
                                <div style="font-size:12px;font-weight:600;color:#374151">
                                    Rs {{ number_format($item->final_price, 0) }}
                                </div>
                                <span style="font-size:10px;padding:2px 7px;border-radius:20px;font-weight:500;
                                                            background:{{ $item->status === 'Completed' ? '#dcfce7' : '#fef9c3' }};
                                                            color:{{ $item->status === 'Completed' ? '#166534' : '#854d0e' }}">
                                    {{ $item->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection