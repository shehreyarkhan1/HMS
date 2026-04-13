@extends('layouts.master')

@section('title', 'Lab Orders')
@section('page-title', 'Laboratory — Orders')
@section('breadcrumb', 'Home / Laboratory / Orders')

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

        /* Status badges */
        .badge-Pending         { background:#fef9c3;color:#854d0e;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
        .badge-Sample-Collected{ background:#e0f2fe;color:#0369a1;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
        .badge-Processing      { background:#fef3c7;color:#92400e;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
        .badge-Completed       { background:#dcfce7;color:#166534;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
        .badge-Cancelled       { background:#fee2e2;color:#991b1b;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }

        /* Priority badges */
        .priority-STAT    { background:#fee2e2;color:#dc2626;font-size:10px;padding:2px 8px;border-radius:20px;font-weight:700;letter-spacing:.04em }
        .priority-Urgent  { background:#fef3c7;color:#d97706;font-size:10px;padding:2px 8px;border-radius:20px;font-weight:600 }
        .priority-Routine { background:#f1f5f9;color:#64748b;font-size:10px;padding:2px 8px;border-radius:20px;font-weight:500 }

        /* Payment badges */
        .pay-Unpaid  { background:#fee2e2;color:#991b1b;font-size:10px;padding:2px 8px;border-radius:20px;font-weight:500 }
        .pay-Partial { background:#dbeafe;color:#1e40af;font-size:10px;padding:2px 8px;border-radius:20px;font-weight:500 }
        .pay-Paid    { background:#dcfce7;color:#166534;font-size:10px;padding:2px 8px;border-radius:20px;font-weight:500 }
    </style>
@endpush

@section('content')

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Today's Orders</div>
                <div class="stat-pill-value">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Pending</div>
                <div class="stat-pill-value" style="color:#ca8a04">{{ $stats['pending'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Processing</div>
                <div class="stat-pill-value" style="color:#d97706">{{ $stats['processing'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Completed</div>
                <div class="stat-pill-value" style="color:#16a34a">{{ $stats['completed'] }}</div>
            </div>
        </div>
    </div>

    {{-- STAT Alert --}}
    @if($stats['stat'] > 0)
        <div class="alert d-flex align-items-center gap-2 mb-4"
            style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;font-size:13px">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <strong>{{ $stats['stat'] }} STAT order(s)</strong> require immediate attention today.
        </div>
    @endif

    {{-- Table --}}
    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-flask me-2 text-primary"></i>All lab orders
            </span>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" action="{{ route('lab.orders.index') }}"
                    class="d-flex gap-2 flex-wrap filter-bar">
                    <input type="text" name="search" placeholder="Order no, patient..."
                        value="{{ request('search') }}" style="width:200px">
                    <select name="status">
                        <option value="">All status</option>
                        @foreach(['Pending','Sample Collected','Processing','Completed','Cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    <select name="priority">
                        <option value="">All priority</option>
                        @foreach(['Routine','Urgent','STAT'] as $p)
                            <option value="{{ $p }}" {{ request('priority') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date" value="{{ request('date') }}" style="width:150px">
                    <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    @if(request()->hasAny(['search','status','priority','date','payment_status']))
                        <a href="{{ route('lab.orders.index') }}" class="btn btn-sm btn-outline-secondary px-3"
                            style="height:36px;font-size:13px">Clear</a>
                    @endif
                </form>
                <a href="{{ route('lab.orders.create') }}" class="btn btn-sm btn-success px-3"
                    style="height:36px;font-size:13px">
                    <i class="bi bi-plus-lg me-1"></i>New order
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th>Order No.</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Priority</th>
                        <th>Tests</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <code style="font-size:12px;color:#6366f1">{{ $order->order_number }}</code>
                            </td>
                            <td>
                                <div style="font-weight:500;color:#1e293b">{{ $order->patient->name }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $order->patient->mrn }}</div>
                            </td>
                            <td style="color:#64748b">{{ $order->doctor->name ?? '—' }}</td>
                            <td style="color:#64748b;font-size:12px">
                                {{ $order->order_date->format('d M Y') }}<br>
                                <span style="color:#94a3b8">{{ $order->order_date->format('H:i') }}</span>
                            </td>
                            <td>
                                <span class="priority-{{ $order->priority }}">{{ $order->priority }}</span>
                            </td>
                            <td>
                                <span style="font-size:12px;font-weight:500;color:#374151">
                                    {{ $order->items->count() }} test(s)
                                </span>
                            </td>
                            <td>
                                <div style="font-size:12px;font-weight:600;color:#1e293b">
                                    Rs {{ number_format($order->net_amount, 0) }}
                                </div>
                                @if($order->discount > 0)
                                    <div style="font-size:11px;color:#94a3b8">
                                        -Rs {{ number_format($order->discount, 0) }} disc.
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="pay-{{ $order->payment_status }}">{{ $order->payment_status }}</span>
                            </td>
                            <td>
                                <span class="badge-{{ str_replace(' ', '-', $order->status) }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('lab.orders.show', $order->id) }}"
                                        class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px"
                                        title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($order->status === 'Pending')
                                        <a href="{{ route('lab.orders.show', $order->id) }}#collect"
                                            class="btn btn-sm btn-warning py-0 px-2" style="font-size:12px"
                                            title="Collect Sample">
                                            <i class="bi bi-droplet-fill"></i>
                                        </a>
                                    @endif
                                    @if(in_array($order->status, ['Sample Collected', 'Processing']))
                                        <a href="{{ route('lab.orders.show', $order->id) }}#results"
                                            class="btn btn-sm btn-success py-0 px-2" style="font-size:12px"
                                            title="Enter Results">
                                            <i class="bi bi-clipboard2-pulse"></i>
                                        </a>
                                    @endif
                                    @if($order->status === 'Completed' && !$order->report_delivered)
                                        <form method="POST"
                                            action="{{ route('lab.orders.deliverReport', $order->id) }}"
                                            class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success py-0 px-2"
                                                style="font-size:12px" title="Mark Delivered">
                                                <i class="bi bi-check2-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5" style="color:#94a3b8">
                                <i class="bi bi-flask" style="font-size:40px;display:block;margin-bottom:8px"></i>
                                No lab orders found.
                                <a href="{{ route('lab.orders.create') }}" class="text-primary ms-1">Create first</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3"
                style="border-top:1px solid #e2e8f0">
                <span style="font-size:12px;color:#94a3b8">
                    Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }}
                    of {{ $orders->total() }}
                </span>
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection