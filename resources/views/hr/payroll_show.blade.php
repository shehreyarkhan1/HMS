
@extends('layouts.master')
@section('title', 'Payroll Run')
@section('page-title', 'Payroll Run')
@section('breadcrumb', 'HR / Payroll / Details')

@push('styles')
<style>
.stat-card{background:#fff;border-radius:12px;padding:1.25rem 1.5rem;border:1px solid #e2e8f0;height:100%;transition:box-shadow .15s}
.stat-label{font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem}
.stat-value{font-size:26px;font-weight:700;color:#1e293b;line-height:1;margin-bottom:.3rem}
.stat-sub{font-size:12px;color:#94a3b8}
.stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
.section-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden}
.section-header{padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between}
.section-title{font-size:14px;font-weight:600;color:#1e293b}
.status-badge{font-size:11px;font-weight:700;padding:4px 14px;border-radius:20px}
.s-draft{background:#f1f5f9;color:#64748b}
.s-processed{background:#fef3c7;color:#b45309}
.s-approved{background:#ede9fe;color:#7c3aed}
.s-paid{background:#dcfce7;color:#16a34a}
table th{font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;background:#f8fafc;border-bottom:1px solid #e2e8f0!important;padding:.7rem 1rem!important}
table td{font-size:13px;padding:.7rem 1rem!important;vertical-align:middle!important;border-bottom:1px solid #f8fafc!important}
table tbody tr:hover{background:#f8fafc}
.emp-cell{display:flex;align-items:center;gap:8px}
.emp-av{width:32px;height:32px;border-radius:50%;background:#dbeafe;color:#1d4ed8;font-size:11px;font-weight:600;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.btn-icon{width:28px;height:28px;border-radius:7px;border:1px solid #e2e8f0;display:inline-flex;align-items:center;justify-content:center;font-size:12px;color:#64748b;text-decoration:none;transition:all .15s;background:#fff}
.btn-icon:hover{background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe}
</style>
@endpush

@section('content')
@php
    $statusClass = match($payroll->status) {
        'Paid'      => 's-paid',
        'Approved'  => 's-approved',
        'Processed' => 's-processed',
        default     => 's-draft',
    };
@endphp

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">{{ $payroll->month_name }}</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">
            Processed: {{ $payroll->processed_at?->format('d M Y') ?? '—' }} &middot;
            By: {{ $payroll->createdBy?->name ?? '—' }}
        </p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <span class="status-badge {{ $statusClass }}">{{ $payroll->status }}</span>

        @if($payroll->status === 'Processed')
        <form action="{{ route('hr.payroll.approve', $payroll) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm text-white" onclick="return confirm('Approve this payroll?')">
                <i class="bi bi-check-lg me-1"></i> Approve
            </button>
        </form>
        @endif

        @if($payroll->status === 'Approved')
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#markPaidModal">
            <i class="bi bi-cash me-1"></i> Mark Paid
        </button>
        @endif

        <a href="{{ route('hr.payroll.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>

{{-- Summary stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['Employees',    $payroll->total_employees, 'bi-people',     '#eff6ff','#1d4ed8','processed'],
        ['Total Gross',  'PKR '.number_format($payroll->total_gross??0), 'bi-cash','#fef3c7','#b45309','gross salary'],
        ['Total Deductions','PKR '.number_format($payroll->total_deductions??0),'bi-dash-circle','#fee2e2','#dc2626','deducted'],
        ['Net Payable',  'PKR '.number_format($payroll->total_net??0), 'bi-cash-stack','#dcfce7','#16a34a','to be paid'],
    ] as [$lbl,$val,$ico,$bg,$clr,$sub])
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">{{ $lbl }}</div><div class="stat-value" style="font-size:20px">{{ $val }}</div><div class="stat-sub">{{ $sub }}</div></div>
                <div class="stat-icon" style="background:{{ $bg }};color:{{ $clr }}"><i class="bi {{ $ico }}"></i></div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Payslips table --}}
<div class="section-card">
    <div class="section-header">
        <span class="section-title"><i class="bi bi-receipt me-2" style="color:#1d4ed8"></i>Payslips</span>
        <span style="font-size:12px;color:#94a3b8">{{ $payslips->total() }} payslips</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr>
                <th>#</th><th>Employee</th>
                <th class="text-end">Basic</th>
                <th class="text-end">Gross</th>
                <th class="text-end">Deductions</th>
                <th class="text-end">Net</th>
                <th class="text-center">Attendance</th>
                <th class="text-center">Status</th>
                <th class="text-center">Actions</th>
            </tr></thead>
            <tbody>
            @forelse($payslips as $ps)
            <tr>
                <td style="color:#94a3b8;font-size:11px">{{ $ps->payslip_number }}</td>
                <td>
                    <div class="emp-cell">
                        <div class="emp-av">{{ strtoupper(substr($ps->employee->first_name??'E',0,1).substr($ps->employee->last_name??'',0,1)) }}</div>
                        <div>
                            <div style="font-weight:600">{{ $ps->employee->first_name }} {{ $ps->employee->last_name }}</div>
                            <div style="font-size:11px;color:#94a3b8">{{ $ps->employee->department }}</div>
                        </div>
                    </div>
                </td>
                <td class="text-end">{{ number_format($ps->basic_salary) }}</td>
                <td class="text-end">{{ number_format($ps->gross_salary) }}</td>
                <td class="text-end" style="color:#dc2626">{{ number_format($ps->total_deductions) }}</td>
                <td class="text-end" style="color:#16a34a;font-weight:700">{{ number_format($ps->net_salary) }}</td>
                <td class="text-center" style="font-size:11px;color:#64748b">
                    P:{{ $ps->present_days }} A:{{ $ps->absent_days }} L:{{ $ps->late_days }}
                </td>
                <td class="text-center">
                    <span style="font-size:10px;font-weight:600;padding:2px 8px;border-radius:20px;background:{{ $ps->status==='Paid' ? '#dcfce7' : '#fef3c7' }};color:{{ $ps->status==='Paid' ? '#16a34a' : '#b45309' }}">
                        {{ $ps->status }}
                    </span>
                </td>
                <td class="text-center">
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="{{ route('hr.payroll.payslip', $ps) }}" class="btn-icon" title="View"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('hr.payroll.payslip.print', $ps) }}" class="btn-icon" title="Print" target="_blank"><i class="bi bi-printer"></i></a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4" style="color:#94a3b8">No payslips found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 d-flex justify-content-center">{{ $payslips->links() }}</div>

{{-- Mark Paid Modal --}}
<div class="modal fade" id="markPaidModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;border:1px solid #e2e8f0">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:1.1rem 1.5rem">
                <h6 class="modal-title fw-semibold" style="color:#1e293b"><i class="bi bi-cash me-2" style="color:#16a34a"></i>Mark Payroll as Paid</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('hr.payroll.mark-paid', $payroll) }}" method="POST">
                @csrf
                <div class="modal-body" style="padding:1.5rem">
                    <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Payment Date <span class="text-danger">*</span></label>
                    <input type="date" name="payment_date" class="form-control" value="{{ today()->toDateString() }}" max="{{ today()->toDateString() }}" required>
                    <div class="mt-3" style="background:#f0fdf4;border-radius:10px;padding:.85rem;font-size:12px;color:#16a34a;border:1px solid #bbf7d0">
                        <i class="bi bi-info-circle me-1"></i>
                        This will mark all <strong>{{ $payroll->total_employees }}</strong> payslips as paid.
                        Total: <strong>PKR {{ number_format($payroll->total_net??0) }}</strong>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:1rem 1.5rem">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg me-1"></i>Confirm Paid</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
