@extends('layouts.master')

@section('title', 'Radiology Orders')
@section('page-title', 'Radiology — Orders')
@section('breadcrumb', 'Home / Radiology / Orders')

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
        .badge-Pending {
            background: #fef9c3;
            color: #854d0e;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-Scheduled {
            background: #dbeafe;
            color: #1e40af;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-In-Progress {
            background: #fef3c7;
            color: #92400e;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-Scan-Completed {
            background: #e0f2fe;
            color: #0369a1;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-Reporting {
            background: #f3e8ff;
            color: #6b21a8;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-Reported {
            background: #d1fae5;
            color: #065f46;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-Verified {
            background: #dcfce7;
            color: #166534;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-Delivered {
            background: #f0fdf4;
            color: #15803d;
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

        /* Priority */
        .priority-STAT {
            background: #fee2e2;
            color: #dc2626;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 700;
            letter-spacing: .04em
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

        /* Payment */
        .pay-Unpaid {
            background: #fee2e2;
            color: #991b1b;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500
        }

        .pay-Partial {
            background: #dbeafe;
            color: #1e40af;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500
        }

        .pay-Paid {
            background: #dcfce7;
            color: #166534;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500
        }

        .critical-row td {
            background: #fff7f7 !important
        }

        .critical-badge {
            background: #fee2e2;
            color: #dc2626;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 700;
            letter-spacing: .03em;
            display: inline-flex;
            align-items: center;
            gap: 3px
        }

        .modality-tag {
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 500
        }
    </style>
@endpush

@section('content')

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Today's Orders</div>
                <div class="stat-pill-value">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Pending / Sched.</div>
                <div class="stat-pill-value" style="color:#ca8a04">{{ $stats['pending'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">In Progress</div>
                <div class="stat-pill-value" style="color:#d97706">{{ $stats['inprogress'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Reported</div>
                <div class="stat-pill-value" style="color:#16a34a">{{ $stats['reported'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">STAT Orders</div>
                <div class="stat-pill-value" style="color:#dc2626">{{ $stats['stat'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Critical Today</div>
                <div class="stat-pill-value" style="color:#9f1239">{{ $stats['critical'] }}</div>
            </div>
        </div>
    </div>

    {{-- STAT Alert --}}
    @if($stats['stat'] > 0)
        <div class="alert d-flex align-items-center gap-2 mb-3"
            style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;font-size:13px">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <strong>{{ $stats['stat'] }} STAT order(s)</strong>&nbsp;require immediate attention.
        </div>
    @endif

    {{-- Critical Alert --}}
    @if($stats['critical'] > 0)
        <div class="alert d-flex align-items-center gap-2 mb-4"
            style="background:#fff1f2;border:1px solid #fda4af;border-radius:10px;color:#9f1239;font-size:13px">
            <i class="bi bi-radiation fs-5"></i>
            <strong>{{ $stats['critical'] }} order(s)</strong>&nbsp;have critical radiological findings — doctor notification
            required.
        </div>
    @endif

    {{-- Table --}}
    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-radioactive me-2 text-primary"></i>All radiology orders
            </span>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" action="{{ route('radiology.orders.index') }}" class="d-flex gap-2 flex-wrap filter-bar">
                    <input type="text" name="search" placeholder="Order no, patient..." value="{{ request('search') }}"
                        style="width:190px">
                    <select name="status">
                        <option value="">All status</option>
                        @foreach(['Pending', 'Scheduled', 'In Progress', 'Scan Completed', 'Reporting', 'Reported', 'Verified', 'Delivered', 'Cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    <select name="priority">
                        <option value="">All priority</option>
                        @foreach(['Routine', 'Urgent', 'STAT'] as $p)
                            <option value="{{ $p }}" {{ request('priority') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                    <select name="modality_id">
                        <option value="">All modality</option>
                        @foreach($modalities as $m)
                            <option value="{{ $m->id }}" {{ request('modality_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="date" value="{{ request('date') }}" style="width:148px">
                    <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'priority', 'modality_id', 'date', 'payment_status']))
                        <a href="{{ route('radiology.orders.index') }}" class="btn btn-sm btn-outline-secondary px-3"
                            style="height:36px;font-size:13px">Clear</a>
                    @endif
                </form>
                <a href="{{ route('radiology.orders.create') }}" class="btn btn-sm btn-success px-3"
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
                        <th>Date / Scheduled</th>
                        <th>Priority</th>
                        <th>Exams</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="{{ $order->hasCriticalFindings() ? 'critical-row' : '' }}">
                            <td>
                                <code style="font-size:12px;color:#6366f1">{{ $order->order_number }}</code>
                                @if($order->hasCriticalFindings())
                                    <br>
                                    <span class="critical-badge mt-1">
                                        <i class="bi bi-exclamation-circle-fill"></i> Critical
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:500;color:#1e293b">{{ $order->patient->name }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $order->patient->mrn }}</div>
                            </td>
                            <td style="color:#64748b">{{ $order->doctor->name ?? '—' }}</td>
                            <td style="color:#64748b;font-size:12px">
                                {{ $order->order_date->format('d M Y') }}<br>
                                @if($order->scheduled_at)
                                    <span style="color:#6366f1;font-size:11px">
                                        <i class="bi bi-calendar2-check"></i>
                                        {{ $order->scheduled_at->format('d M H:i') }}
                                    </span>
                                @else
                                    <span style="color:#94a3b8">Not scheduled</span>
                                @endif
                            </td>
                            <td>
                                <span class="priority-{{ $order->priority }}">{{ $order->priority }}</span>
                            </td>
                            <td>
                                @foreach($order->items->take(2) as $item)
                                    <span class="modality-tag d-inline-block mb-1">
                                        {{ $item->exam->modality->name ?? '—' }}
                                    </span>
                                @endforeach
                                @if($order->items->count() > 2)
                                    <span style="font-size:11px;color:#94a3b8">
                                        +{{ $order->items->count() - 2 }} more
                                    </span>
                                @endif
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
                                <div class="d-flex justify-content-end gap-1 flex-wrap">
                                    {{-- View --}}
                                    <a href="{{ route('radiology.orders.show', $order->id) }}"
                                        class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Schedule --}}
                                    @if($order->status === 'Pending')
                                        <a href="{{ route('radiology.orders.show', $order->id) }}#schedule"
                                            class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:12px"
                                            title="Schedule">
                                            <i class="bi bi-calendar2-plus"></i>
                                        </a>
                                    @endif

                                    {{-- Start Scan --}}
                                    @if(in_array($order->status, ['Pending', 'Scheduled']))
                                        <a href="{{ route('radiology.orders.show', $order->id) }}#scan"
                                            class="btn btn-sm btn-warning py-0 px-2" style="font-size:12px" title="Start Scan">
                                            <i class="bi bi-camera-fill"></i>
                                        </a>
                                    @endif

                                    {{-- Enter Report --}}
                                    @if(in_array($order->status, ['Scan Completed', 'Reporting', 'In Progress']))
                                        <a href="{{ route('radiology.orders.show', $order->id) }}#report"
                                            class="btn btn-sm btn-success py-0 px-2" style="font-size:12px" title="Enter Report">
                                            <i class="bi bi-file-earmark-medical"></i>
                                        </a>
                                    @endif

                                    {{-- Deliver --}}
                                    @if(in_array($order->status, ['Reported', 'Verified']) && !$order->report_delivered)
                                        <form method="POST" action="{{ route('radiology.orders.deliverReport', $order->id) }}"
                                            class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success py-0 px-2" style="font-size:12px"
                                                title="Mark Delivered">
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
                                <i class="bi bi-radioactive"
                                    style="font-size:40px;display:block;margin-bottom:8px;opacity:.3"></i>
                                No radiology orders found.
                                <a href="{{ route('radiology.orders.create') }}" class="text-primary ms-1">Create first</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #e2e8f0">
                <span style="font-size:12px;color:#94a3b8">
                    Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }}
                    of {{ $orders->total() }}
                </span>
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection