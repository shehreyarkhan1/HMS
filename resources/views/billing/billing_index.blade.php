@extends('layouts.master')

@section('title', 'Billing & Invoices')
@section('page-title', 'Billing — Invoices')
@section('breadcrumb', 'Home / Billing')

@push('styles')
    <style>
        .stat-pill {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 20px
        }

        .stat-pill-label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em
        }

        .stat-pill-value {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b
        }

        .card-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden
        }

        .card-section-header {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px
        }

        .filter-bar input,
        .filter-bar select {
            height: 36px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            padding: 0 12px;
            color: #374151;
            background: #f8fafc
        }

        .filter-bar input:focus,
        .filter-bar select:focus {
            outline: none;
            border-color: #93c5fd;
            background: #fff
        }

        .table-clean th {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 10px 16px !important;
            background: #f8fafc
        }

        .table-clean td {
            font-size: 13px;
            color: #374151;
            padding: 12px 16px !important;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9 !important
        }

        .table-clean tr:last-child td {
            border-bottom: none !important
        }

        .table-clean tr:hover td {
            background: #fafafa
        }

        .badge-Draft {
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-Finalized {
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

        .type-OPD {
            background: #f0fdf4;
            color: #15803d;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500
        }

        .type-IPD {
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500
        }

        .type-Emergency {
            background: #fef2f2;
            color: #dc2626;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 700
        }
    </style>
@endpush

@section('content')

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Today's Bills</div>
                <div class="stat-pill-value">{{ $stats['today_bills'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Collected Today</div>
                <div class="stat-pill-value" style="color:#16a34a">Rs. {{ number_format($stats['today_collected'], 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Outstanding Dues</div>
                <div class="stat-pill-value" style="color:#dc2626">Rs. {{ number_format($stats['total_due'], 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Draft Bills</div>
                <div class="stat-pill-value" style="color:#d97706">{{ $stats['draft_count'] }}</div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert d-flex align-items-center gap-2 mb-4"
            style="background:#dcfce7;border:1px solid #86efac;border-radius:10px;color:#166534;font-size:13px">
            <i class="bi bi-check-circle-fill fs-5"></i>{{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert d-flex align-items-center gap-2 mb-4"
            style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;font-size:13px">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>{{ session('error') }}
        </div>
    @endif

    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-receipt me-2 text-warning"></i>All Bills & Invoices
            </span>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" action="{{ route('billing.index') }}" class="d-flex gap-2 flex-wrap filter-bar">
                    <input type="text" name="search" placeholder="Bill #, patient, MRN..."
                        value="{{ request('search') }}" style="width:200px">
                    <select name="status">
                        <option value="">All Status</option>
                        @foreach (['Draft', 'Finalized', 'Cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                                {{ $s }}</option>
                        @endforeach
                    </select>
                    <select name="payment_status">
                        <option value="">All Payments</option>
                        @foreach (['Unpaid', 'Partial', 'Paid'] as $ps)
                            <option value="{{ $ps }}" {{ request('payment_status') == $ps ? 'selected' : '' }}>
                                {{ $ps }}</option>
                        @endforeach
                    </select>
                    <select name="bill_type">
                        <option value="">All Types</option>
                        @foreach (['OPD', 'IPD', 'Emergency'] as $t)
                            <option value="{{ $t }}" {{ request('bill_type') == $t ? 'selected' : '' }}>
                                {{ $t }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" style="width:140px">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" style="width:140px">
                    <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    @if (request()->hasAny(['search', 'status', 'payment_status', 'bill_type', 'date_from', 'date_to']))
                        <a href="{{ route('billing.index') }}" class="btn btn-sm btn-outline-secondary px-3"
                            style="height:36px;font-size:13px">Clear</a>
                    @endif
                </form>
                <a href="{{ route('billing.service-charges.index') }}" class="btn btn-sm btn-outline-secondary px-3"
                    style="height:36px;font-size:13px">
                    <i class="bi bi-gear me-1"></i>Service Charges
                </a>
                <a href="{{ route('billing.create') }}" class="btn btn-sm btn-success px-3"
                    style="height:36px;font-size:13px">
                    <i class="bi bi-plus-lg me-1"></i>New Bill
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th>Bill #</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Net Amount</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bills as $bill)
                        <tr>
                            <td>
                                <a href="{{ route('billing.show', $bill) }}"
                                    style="font-size:12px;font-weight:600;color:#6366f1;text-decoration:none;font-family:monospace">
                                    {{ $bill->bill_number }}
                                </a>
                            </td>
                            <td>
                                <div style="font-weight:500;color:#1e293b">{{ $bill->patient->name }}</div>
                                <div style="font-size:11px;color:#94a3b8">MRN: {{ $bill->patient->mrn }}</div>
                            </td>
                            <td style="color:#64748b;font-size:12px">{{ $bill->bill_date->format('d M Y') }}</td>
                            <td><span class="type-{{ $bill->bill_type }}">{{ $bill->bill_type }}</span></td>
                            <td style="font-size:13px;font-weight:600;color:#1e293b">Rs.
                                {{ number_format($bill->net_amount, 0) }}</td>
                            <td style="font-size:12px;font-weight:500;color:#16a34a">Rs.
                                {{ number_format($bill->paid_amount, 0) }}</td>
                            <td
                                style="font-size:12px;font-weight:500;color:{{ $bill->due_amount > 0 ? '#dc2626' : '#94a3b8' }}">
                                Rs. {{ number_format($bill->due_amount, 0) }}
                            </td>
                            <td><span class="badge-{{ $bill->status }}">{{ $bill->status }}</span></td>
                            <td><span class="pay-{{ $bill->payment_status }}">{{ $bill->payment_status }}</span></td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('billing.show', $bill) }}"
                                        class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px"
                                        title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if ($bill->isDraft())
                                        <a href="{{ route('billing.edit', $bill) }}"
                                            class="btn btn-sm btn-outline-warning py-0 px-2" style="font-size:12px"
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                    @if ($bill->isFinalized() && !$bill->isPaid())
                                        <a href="{{ route('billing.show', $bill) }}#payment"
                                            class="btn btn-sm btn-success py-0 px-2" style="font-size:12px"
                                            title="Record Payment">
                                            <i class="bi bi-cash-coin"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('billing.print', $bill) }}" target="_blank"
                                        class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:12px"
                                        title="Print Invoice">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5" style="color:#94a3b8">
                                <i class="bi bi-receipt" style="font-size:40px;display:block;margin-bottom:8px"></i>
                                No bills found.
                                <a href="{{ route('billing.create') }}" class="text-primary ms-1">Create first bill</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($bills->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #e2e8f0">
                <span style="font-size:12px;color:#94a3b8">Showing {{ $bills->firstItem() }}–{{ $bills->lastItem() }} of
                    {{ $bills->total() }}</span>
                {{ $bills->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection
