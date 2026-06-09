@extends('layouts.master')
@section('title', 'Payroll')
@section('page-title', 'Payroll Runs')
@section('breadcrumb', 'HR / Payroll')

@push('styles')
<style>
.stat-card{background:#fff;border-radius:12px;padding:1.25rem 1.5rem;border:1px solid #e2e8f0;height:100%;transition:box-shadow .15s}
.stat-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.07)}
.stat-label{font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem}
.stat-value{font-size:28px;font-weight:700;color:#1e293b;line-height:1;margin-bottom:.3rem}
.stat-sub{font-size:12px;color:#94a3b8}
.stat-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
.section-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden}
.section-header{padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between}
.section-title{font-size:14px;font-weight:600;color:#1e293b}
.run-row{display:flex;align-items:center;padding:.9rem 1.25rem;border-bottom:1px solid #f8fafc;gap:14px;transition:background .1s}
.run-row:last-child{border-bottom:none}
.run-row:hover{background:#f8fafc}
.month-box{width:50px;height:50px;border-radius:10px;background:#eff6ff;color:#1d4ed8;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0}
.month-name{font-size:11px;font-weight:700;text-transform:uppercase}
.month-year{font-size:10px;color:#64748b}
.run-info{flex:1;min-width:0}
.run-title{font-size:13px;font-weight:600;color:#1e293b}
.run-meta{font-size:11px;color:#94a3b8;margin-top:2px}
.s-draft{background:#f1f5f9;color:#64748b}
.s-processing{background:#dbeafe;color:#1d4ed8}
.s-processed{background:#fef3c7;color:#b45309}
.s-approved{background:#ede9fe;color:#7c3aed}
.s-paid{background:#dcfce7;color:#16a34a}
.status-badge{font-size:10px;font-weight:600;padding:3px 10px;border-radius:20px;white-space:nowrap}
.empty-state{padding:3rem;text-align:center;color:#94a3b8;font-size:13px}
.empty-state i{font-size:32px;display:block;margin-bottom:.75rem;color:#cbd5e1}
.btn-icon{width:28px;height:28px;border-radius:7px;border:1px solid #e2e8f0;display:inline-flex;align-items:center;justify-content:center;font-size:12px;color:#64748b;text-decoration:none;transition:all .15s;background:#fff}
.btn-icon:hover{background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe}
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">Payroll Runs</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">Monthly payroll processing history</p>
    </div>
    <a href="{{ route('hr.payroll.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Process Payroll
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-4">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Total Paid Out</div><div class="stat-value">{{ number_format($stats['total_paid']/1000,0) }}K</div><div class="stat-sub">PKR all time</div></div>
                <div class="stat-icon" style="background:#dcfce7;color:#16a34a"><i class="bi bi-cash-stack"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-4">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Pending Runs</div><div class="stat-value">{{ $stats['pending_runs'] }}</div><div class="stat-sub">need approval / payment</div></div>
                <div class="stat-icon" style="background:#fef3c7;color:#b45309"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label">This Month</div>
                    @if($stats['this_month'])
                        <div class="stat-value">{{ number_format($stats['this_month']->total_net/1000,0) }}K</div>
                        <div class="stat-sub">{{ $stats['this_month']->total_employees }} employees &middot; {{ $stats['this_month']->status }}</div>
                    @else
                        <div class="stat-value" style="font-size:18px;color:#94a3b8">Not Run</div>
                        <div class="stat-sub"><a href="{{ route('hr.payroll.create') }}" style="color:#1d4ed8">Process now</a></div>
                    @endif
                </div>
                <div class="stat-icon" style="background:#eff6ff;color:#1d4ed8"><i class="bi bi-calendar-check"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <span class="section-title"><i class="bi bi-list-check me-2" style="color:#1d4ed8"></i>Payroll History</span>
    </div>
    @forelse($runs as $run)
    @php
        $statusClass = match($run->status) {
            'Paid'       => 's-paid',
            'Approved'   => 's-approved',
            'Processed'  => 's-processed',
            'Processing' => 's-processing',
            default      => 's-draft',
        };
    @endphp
    <div class="run-row">
        <div class="month-box">
            <div class="month-name">{{ \Carbon\Carbon::createFromDate($run->year,$run->month)->format('M') }}</div>
            <div class="month-year">{{ $run->year }}</div>
        </div>
        <div class="run-info">
            <div class="run-title">{{ $run->month_name }}</div>
            <div class="run-meta">
                {{ $run->payslips_count }} employees &middot;
                Gross: PKR {{ number_format($run->total_gross ?? 0) }} &middot;
                Net: <strong style="color:#16a34a">PKR {{ number_format($run->total_net ?? 0) }}</strong>
                @if($run->payment_date) &middot; Paid: {{ \Carbon\Carbon::parse($run->payment_date)->format('d M Y') }} @endif
            </div>
        </div>
        <span class="status-badge {{ $statusClass }}">{{ $run->status }}</span>
        <a href="{{ route('hr.payroll.show', $run) }}" class="btn-icon" title="View"><i class="bi bi-eye"></i></a>
    </div>
    @empty
        <div class="empty-state"><i class="bi bi-calendar-x"></i>No payroll runs yet.</div>
    @endforelse
</div>

<div class="mt-3 d-flex justify-content-center">{{ $runs->links() }}</div>
@endsection
