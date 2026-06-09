@extends('layouts.master')
@section('title', 'Salary History')
@section('page-title', 'Salary History')
@section('breadcrumb', 'HR / Salary / History')

@push('styles')
<style>
    .section-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;margin-bottom:1rem}
    .section-header{padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between}
    .section-title{font-size:14px;font-weight:600;color:#1e293b}
    .emp-profile{background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:1.5rem;margin-bottom:1rem;display:flex;align-items:center;gap:1rem}
    .emp-av-lg{width:56px;height:56px;border-radius:14px;background:#dbeafe;color:#1d4ed8;font-size:20px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .struct-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;margin-bottom:1rem}
    .struct-header{padding:.85rem 1.25rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between}
    .detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:0}
    .detail-item{padding:.6rem 1.25rem;border-bottom:1px solid #f8fafc;display:flex;justify-content:space-between;align-items:center}
    .detail-item:nth-child(odd){border-right:1px solid #f8fafc}
    .d-label{font-size:12px;color:#94a3b8;font-weight:500}
    .d-val{font-size:13px;font-weight:600;color:#1e293b}
    .current-badge{font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;background:#dcfce7;color:#16a34a}
    .old-badge{font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;background:#f1f5f9;color:#94a3b8}
    .section-divider{padding:.5rem 1.25rem;background:#f8fafc;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #f1f5f9}
    .total-row{background:#f8fafc;font-weight:700}
    .deduction-val{color:#dc2626}
    .net-val{color:#16a34a;font-size:15px}
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">Salary History</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">All salary structures for this employee</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('hr.salary.create', $employee) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> New Structure
        </a>
        <a href="{{ route('hr.salary.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>

{{-- Employee profile --}}
<div class="emp-profile">
    <div class="emp-av-lg">{{ strtoupper(substr($employee->first_name,0,1).substr($employee->last_name,0,1)) }}</div>
    <div style="flex:1">
        <div style="font-size:16px;font-weight:700;color:#1e293b">{{ $employee->first_name }} {{ $employee->last_name }}</div>
        <div style="font-size:13px;color:#64748b">{{ $employee->employee_id }} &middot; {{ $employee->department }} &middot; {{ $employee->designation }}</div>
        <div style="font-size:12px;color:#94a3b8;margin-top:3px">Joined: {{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d M Y') : '—' }}</div>
    </div>
    <div style="text-align:right">
        @if($structures->isNotEmpty() && $structures->first()->is_current)
        <div style="font-size:11px;color:#94a3b8">Current Net Salary</div>
        <div style="font-size:24px;font-weight:800;color:#16a34a">PKR {{ number_format($structures->first()->net_salary) }}</div>
        @endif
    </div>
</div>

@forelse($structures as $s)
<div class="struct-card">
    <div class="struct-header">
        <div style="display:flex;align-items:center;gap:10px">
            <span style="font-size:13px;font-weight:600;color:#1e293b">
                Effective from {{ \Carbon\Carbon::parse($s->effective_from)->format('d M Y') }}
            </span>
            @if($s->is_current)
                <span class="current-badge">Current</span>
            @else
                <span class="old-badge">Historical</span>
            @endif
        </div>
        @if($s->is_current)
        <a href="{{ route('hr.salary.edit', [$employee, $s]) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        @endif
    </div>

    {{-- Earnings --}}
    <div class="section-divider">Earnings</div>
    <div class="detail-grid">
        <div class="detail-item"><span class="d-label">Basic Salary</span><span class="d-val">{{ number_format($s->basic_salary) }}</span></div>
        <div class="detail-item"><span class="d-label">House Rent</span><span class="d-val">{{ number_format($s->house_rent_allowance) }}</span></div>
        <div class="detail-item"><span class="d-label">Medical</span><span class="d-val">{{ number_format($s->medical_allowance) }}</span></div>
        <div class="detail-item"><span class="d-label">Transport</span><span class="d-val">{{ number_format($s->transport_allowance) }}</span></div>
        <div class="detail-item"><span class="d-label">Meal</span><span class="d-val">{{ number_format($s->meal_allowance) }}</span></div>
        <div class="detail-item"><span class="d-label">Special</span><span class="d-val">{{ number_format($s->special_allowance) }}</span></div>
        @if($s->other_allowance > 0)
        <div class="detail-item" style="grid-column:1/-1"><span class="d-label">Other ({{ $s->other_allowance_description }})</span><span class="d-val">{{ number_format($s->other_allowance) }}</span></div>
        @endif
        <div class="detail-item total-row" style="grid-column:1/-1"><span class="d-label" style="font-weight:700;color:#1e293b">Gross Salary</span><span class="d-val" style="font-size:15px;color:#1d4ed8">{{ number_format($s->gross_salary) }}</span></div>
    </div>

    {{-- Deductions --}}
    <div class="section-divider">Deductions</div>
    <div class="detail-grid">
        <div class="detail-item"><span class="d-label">Income Tax</span><span class="d-val deduction-val">{{ number_format($s->income_tax_monthly) }}</span></div>
        <div class="detail-item"><span class="d-label">Tax Slab</span><span class="d-val">{{ $s->tax_slab ?: '—' }}</span></div>
        <div class="detail-item"><span class="d-label">EOBI (Employee)</span><span class="d-val deduction-val">{{ number_format($s->eobi_employee_share) }}</span></div>
        <div class="detail-item"><span class="d-label">EOBI (Employer)</span><span class="d-val">{{ number_format($s->eobi_employer_share) }}</span></div>
        <div class="detail-item"><span class="d-label">Provident Fund</span><span class="d-val deduction-val">{{ number_format($s->provident_fund) }}</span></div>
        <div class="detail-item"><span class="d-label">Loan</span><span class="d-val deduction-val">{{ number_format($s->loan_deduction) }}</span></div>
        @if($s->other_deduction > 0)
        <div class="detail-item" style="grid-column:1/-1"><span class="d-label">Other ({{ $s->other_deduction_description }})</span><span class="d-val deduction-val">{{ number_format($s->other_deduction) }}</span></div>
        @endif
        <div class="detail-item total-row" style="grid-column:1/-1"><span class="d-label" style="font-weight:700;color:#1e293b">Total Deductions</span><span class="d-val deduction-val" style="font-size:15px">{{ number_format($s->total_deductions) }}</span></div>
    </div>

    {{-- Net --}}
    <div style="padding:1rem 1.25rem;background:#f0fdf4;border-top:1px solid #bbf7d0;display:flex;justify-content:space-between;align-items:center">
        <span style="font-size:14px;font-weight:700;color:#1e293b">Net Salary</span>
        <span style="font-size:22px;font-weight:800;color:#16a34a">PKR {{ number_format($s->net_salary) }}</span>
    </div>

    @if($s->notes)
    <div style="padding:.75rem 1.25rem;background:#fffbeb;border-top:1px solid #fde68a">
        <span style="font-size:12px;color:#b45309"><i class="bi bi-info-circle me-1"></i>{{ $s->notes }}</span>
    </div>
    @endif
</div>
@empty
<div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:3rem;text-align:center;color:#94a3b8">
    <i class="bi bi-cash-coin" style="font-size:32px;display:block;margin-bottom:.75rem;color:#cbd5e1"></i>
    No salary structure configured.
    <br>
    <a href="{{ route('hr.salary.create', $employee) }}" class="btn btn-primary btn-sm mt-3">
        <i class="bi bi-plus-lg me-1"></i> Create Structure
    </a>
</div>
@endforelse
@endsection
