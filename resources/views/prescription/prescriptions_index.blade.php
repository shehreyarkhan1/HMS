@extends('layouts.master')

@section('title', 'Prescriptions')
@section('page-title', 'Pharmacy — Prescriptions')
@section('breadcrumb', 'Home / Pharmacy / Prescriptions')

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

        .badge-Pending {
            background: #fef9c3;
            color: #854d0e;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-Partial {
            background: #dbeafe;
            color: #1e40af;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-Dispensed {
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
    </style>
@endpush

@section('content')

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Total</div>
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
                <div class="stat-pill-label">Partial</div>
                <div class="stat-pill-value" style="color:#1d4ed8">{{ $stats['partial'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Dispensed</div>
                <div class="stat-pill-value" style="color:#16a34a">{{ $stats['dispensed'] }}</div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-file-medical me-2 text-primary"></i>All prescriptions
            </span>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" action="{{ route('pharmacy.prescriptions.index') }}"
                    class="d-flex gap-2 flex-wrap filter-bar">
                    <input type="text" name="search" placeholder="RX number, patient..." value="{{ request('search') }}"
                        style="width:200px">
                    <select name="status">
                        <option value="">All status</option>
                        @foreach(['Pending', 'Partial', 'Dispensed', 'Cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date" value="{{ request('date') }}" style="width:150px">
                    <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'date']))
                        <a href="{{ route('pharmacy.prescriptions.index') }}" class="btn btn-sm btn-outline-secondary px-3"
                            style="height:36px;font-size:13px">Clear</a>
                    @endif
                </form>
                <a href="{{ route('pharmacy.prescriptions.create') }}" class="btn btn-sm btn-success px-3"
                    style="height:36px;font-size:13px">
                    <i class="bi bi-plus-lg me-1"></i>New prescription
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th>RX No.</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Valid until</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prescriptions as $rx)
                        <tr>
                            <td><code style="font-size:12px;color:#6366f1">{{ $rx->prescription_number }}</code></td>
                            <td>
                                <div style="font-weight:500;color:#1e293b">{{ $rx->patient->name }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $rx->patient->mrn }}</div>
                            </td>
                            <td style="color:#64748b">{{ $rx->doctor->name ?? '—' }}</td>
                            <td style="color:#64748b;font-size:12px">{{ $rx->prescribed_date->format('d M Y') }}</td>
                            <td>
                                @if($rx->valid_until)
                                    <span style="font-size:12px;color:{{ $rx->is_expired ? '#dc2626' : '#64748b' }}">
                                        {{ $rx->valid_until->format('d M Y') }}
                                        @if($rx->is_expired) <i class="bi bi-exclamation-circle ms-1"></i> @endif
                                    </span>
                                @else
                                    <span style="color:#94a3b8">—</span>
                                @endif
                            </td>
                            <td>
                                <span style="font-size:12px;font-weight:500;color:#374151">
                                    {{ $rx->items->count() }} medicine(s)
                                </span>
                            </td>
                            <td><span class="badge-{{ $rx->status }}">{{ $rx->status }}</span></td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('pharmacy.prescriptions.show', $rx->id) }}"
                                        class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(in_array($rx->status, ['Pending', 'Partial']))
                                        <a href="{{ route('pharmacy.dispensings.create', ['prescription_id' => $rx->id]) }}"
                                            class="btn btn-sm btn-success py-0 px-2" style="font-size:12px" title="Dispense">
                                            <i class="bi bi-capsule"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5" style="color:#94a3b8">
                                <i class="bi bi-file-medical" style="font-size:40px;display:block;margin-bottom:8px"></i>
                                No prescriptions found.
                                <a href="{{ route('pharmacy.prescriptions.create') }}" class="text-primary ms-1">Create
                                    first</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($prescriptions->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #e2e8f0">
                <span style="font-size:12px;color:#94a3b8">
                    Showing {{ $prescriptions->firstItem() }}–{{ $prescriptions->lastItem() }}
                    of {{ $prescriptions->total() }}
                </span>
                {{ $prescriptions->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection