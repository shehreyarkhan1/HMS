@extends('layouts.master')

@section('title', 'Dispensing')
@section('page-title', 'Pharmacy — Dispensing')
@section('breadcrumb', 'Home / Pharmacy / Dispensing')

@push('styles')
<style>
    .stat-pill{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:14px 20px}
    .stat-pill-label{font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em}
    .stat-pill-value{font-size:22px;font-weight:700;color:#1e293b}
    .card-section{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden}
    .card-section-header{padding:14px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}
    .filter-bar input,.filter-bar select{height:36px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;padding:0 12px;color:#374151;background:#f8fafc}
    .table-clean th{font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0 !important;padding:10px 16px !important;background:#f8fafc}
    .table-clean td{font-size:13px;color:#374151;padding:12px 16px !important;vertical-align:middle;border-bottom:1px solid #f1f5f9 !important}
    .table-clean tr:last-child td{border-bottom:none !important}
    .table-clean tr:hover td{background:#fafafa}
    .pay-Paid   {background:#dcfce7;color:#166534;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500}
    .pay-Unpaid {background:#fee2e2;color:#991b1b;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500}
    .pay-Partial{background:#fef9c3;color:#854d0e;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500}
</style>
@endpush

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Today's count</div>
            <div class="stat-pill-value">{{ $stats['today_count'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Today's revenue</div>
            <div class="stat-pill-value" style="color:#16a34a">Rs {{ number_format($stats['today_revenue'], 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Unpaid</div>
            <div class="stat-pill-value" style="color:#dc2626">{{ $stats['unpaid'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Total revenue</div>
            <div class="stat-pill-value" style="font-size:18px">Rs {{ number_format($stats['total_revenue'], 0) }}</div>
        </div>
    </div>
</div>

<div class="card-section">
    <div class="card-section-header">
        <span style="font-size:14px;font-weight:600;color:#1e293b">
            <i class="bi bi-capsule me-2 text-primary"></i>Dispensing records
        </span>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <form method="GET" action="{{ route('pharmacy.dispensings.index') }}" class="d-flex gap-2 filter-bar">
                <input type="text" name="search" placeholder="DSP no., patient..."
                       value="{{ request('search') }}" style="width:180px">
                <select name="payment">
                    <option value="">All payments</option>
                    @foreach(['Paid','Unpaid','Partial'] as $p)
                        <option value="{{ $p }}" {{ request('payment') == $p ? 'selected':'' }}>{{ $p }}</option>
                    @endforeach
                </select>
                <input type="date" name="date" value="{{ request('date') }}" style="width:150px">
                <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                @if(request()->hasAny(['search','payment','date']))
                    <a href="{{ route('pharmacy.dispensings.index') }}"
                       class="btn btn-sm btn-outline-secondary" style="height:36px;font-size:13px">Clear</a>
                @endif
            </form>
            <a href="{{ route('pharmacy.dispensings.create') }}"
               class="btn btn-sm btn-success px-3" style="height:36px;font-size:13px">
                <i class="bi bi-plus-lg me-1"></i>New dispense
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>DSP No.</th>
                    <th>Patient</th>
                    <th>Prescription</th>
                    <th>Date & time</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dispensings as $dsp)
                <tr>
                    <td><code style="font-size:12px;color:#6366f1">{{ $dsp->dispense_number }}</code></td>
                    <td>
                        <div style="font-weight:500">{{ $dsp->patient->name }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $dsp->patient->mrn }}</div>
                    </td>
                    <td>
                        @if($dsp->prescription)
                            <code style="font-size:11px;color:#6366f1">{{ $dsp->prescription->prescription_number }}</code>
                        @else
                            <span style="color:#94a3b8;font-size:12px">Walk-in</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#64748b">{{ $dsp->dispensed_at->format('d M Y h:i A') }}</td>
                    <td style="font-size:12px;color:#374151">{{ $dsp->items->count() }} item(s)</td>
                    <td style="font-weight:600;color:#1e293b">Rs {{ number_format($dsp->total_amount, 2) }}</td>
                    <td><span class="pay-{{ $dsp->payment_status }}">{{ $dsp->payment_status }}</span></td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('pharmacy.dispensings.show', $dsp->id) }}"
                               class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($dsp->payment_status !== 'Paid')
                                <form method="POST" action="{{ route('pharmacy.dispensings.mark-paid', $dsp->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success py-0 px-2"
                                            style="font-size:12px" title="Mark Paid">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5" style="color:#94a3b8">
                        <i class="bi bi-capsule" style="font-size:40px;display:block;margin-bottom:8px"></i>
                        No dispensing records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($dispensings->hasPages())
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #e2e8f0">
        <span style="font-size:12px;color:#94a3b8">
            Showing {{ $dispensings->firstItem() }}–{{ $dispensings->lastItem() }}
            of {{ $dispensings->total() }}
        </span>
        {{ $dispensings->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection