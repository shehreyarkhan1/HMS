
@extends('layouts.master')
@section('title', 'Payslip')
@section('page-title', 'Payslip')
@section('breadcrumb', 'HR / Payroll / Payslip')

@push('styles')
<style>
.payslip-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;max-width:800px;margin:0 auto}
.ps-header{background:linear-gradient(135deg,#1d4ed8,#1e40af);color:#fff;padding:2rem}
.ps-company{font-size:20px;font-weight:800}
.ps-period{font-size:13px;opacity:.8;margin-top:4px}
.ps-emp-name{font-size:22px;font-weight:700}
.ps-emp-meta{font-size:12px;opacity:.75;margin-top:4px}
.ps-body{padding:1.5rem}
.ps-section{margin-bottom:1.25rem}
.ps-section-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;padding-bottom:.4rem;border-bottom:1px solid #f1f5f9}
.ps-row{display:flex;justify-content:space-between;padding:.35rem 0;border-bottom:1px solid #f8fafc}
.ps-row:last-child{border-bottom:none}
.ps-row-label{font-size:12px;color:#64748b}
.ps-row-val{font-size:13px;font-weight:600;color:#1e293b}
.ps-total-row{display:flex;justify-content:space-between;padding:.6rem 0;border-top:2px solid #e2e8f0;margin-top:.4rem}
.ps-total-label{font-size:13px;font-weight:700;color:#1e293b}
.ps-total-val{font-size:14px;font-weight:800}
.ps-net{background:#f0fdf4;border-radius:10px;padding:1.25rem;border:1px solid #bbf7d0;display:flex;justify-content:space-between;align-items:center;margin-top:1rem}
.ps-net-label{font-size:14px;font-weight:700;color:#16a34a}
.ps-net-val{font-size:28px;font-weight:800;color:#16a34a}
.att-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:.5rem}
.att-box{background:#f8fafc;border-radius:8px;padding:.6rem;text-align:center;border:1px solid #e2e8f0}
.att-num{font-size:18px;font-weight:700;color:#1e293b;line-height:1}
.att-lbl{font-size:10px;color:#94a3b8;margin-top:2px}
.status-pill{font-size:11px;font-weight:700;padding:3px 12px;border-radius:20px}
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4" style="max-width:800px;margin:0 auto">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">Payslip</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">{{ $payslip->payslip_number }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('hr.payroll.payslip.print', $payslip) }}" class="btn btn-outline-secondary btn-sm" target="_blank">
            <i class="bi bi-printer me-1"></i> Print
        </a>
        <a href="{{ route('hr.payroll.show', $payslip->payrollRun) }}" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="payslip-card">
    {{-- Header --}}
    <div class="ps-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="ps-company">MediCore Hospital</div>
                <div class="ps-period">Salary Slip for {{ $payslip->payrollRun->month_name }}</div>
            </div>
            <div class="text-end">
                @if($payslip->is_paid)
                    <span class="status-pill" style="background:rgba(255,255,255,.2);color:#fff">PAID</span>
                @else
                    <span class="status-pill" style="background:rgba(255,255,255,.15);color:#fff">PENDING</span>
                @endif
            </div>
        </div>
        <div style="border-top:1px solid rgba(255,255,255,.2);margin:1rem 0"></div>
        <div class="d-flex justify-content-between align-items-end">
            <div>
                <div class="ps-emp-name">{{ $payslip->employee->first_name }} {{ $payslip->employee->last_name }}</div>
                <div class="ps-emp-meta">
                    {{ $payslip->employee->employee_id }} &bull;
                    {{ $payslip->employee->designation }} &bull;
                    {{ $payslip->employee->department }}
                </div>
            </div>
            <div class="text-end" style="font-size:12px;opacity:.8">
                @if($payslip->payment_method) {{ $payslip->payment_method }}<br> @endif
                @if($payslip->bank_name) {{ $payslip->bank_name }}<br> @endif
                @if($payslip->bank_account_number) {{ $payslip->bank_account_number }} @endif
            </div>
        </div>
    </div>

    <div class="ps-body">
        {{-- Attendance --}}
        <div class="ps-section">
            <div class="ps-section-title">Attendance Summary</div>
            <div class="att-grid">
                @foreach([
                    [$payslip->total_working_days,'Working Days'],
                    [$payslip->present_days,'Present'],
                    [$payslip->absent_days,'Absent'],
                    [$payslip->late_days,'Late'],
                    [$payslip->half_days,'Half Days'],
                    [$payslip->leave_days,'On Leave'],
                    [$payslip->holiday_days,'Holidays'],
                    [number_format($payslip->overtime_hours,1),'OT Hours'],
                ] as [$num,$lbl])
                <div class="att-box">
                    <div class="att-num">{{ $num }}</div>
                    <div class="att-lbl">{{ $lbl }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="row g-3">
            {{-- Earnings --}}
            <div class="col-12 col-md-6">
                <div class="ps-section">
                    <div class="ps-section-title">Earnings</div>
                    @foreach([
                        ['Basic Salary',      $payslip->basic_salary],
                        ['House Rent',        $payslip->house_rent_allowance],
                        ['Medical',           $payslip->medical_allowance],
                        ['Transport',         $payslip->transport_allowance],
                        ['Meal',              $payslip->meal_allowance],
                        ['Special',           $payslip->special_allowance],
                        ['Other Allowance',   $payslip->other_allowance],
                        ['Overtime',          $payslip->overtime_amount],
                        ['Bonus',             $payslip->bonus],
                        ['Arrears',           $payslip->arrears],
                    ] as [$lbl,$val])
                    @if($val > 0)
                    <div class="ps-row">
                        <span class="ps-row-label">{{ $lbl }}</span>
                        <span class="ps-row-val">{{ number_format($val) }}</span>
                    </div>
                    @endif
                    @endforeach
                    <div class="ps-total-row">
                        <span class="ps-total-label">Gross Salary</span>
                        <span class="ps-total-val" style="color:#1d4ed8">{{ number_format($payslip->gross_salary) }}</span>
                    </div>
                </div>
            </div>

            {{-- Deductions --}}
            <div class="col-12 col-md-6">
                <div class="ps-section">
                    <div class="ps-section-title">Deductions</div>
                    @foreach([
                        ['Income Tax',     $payslip->income_tax_monthly],
                        ['EOBI',           $payslip->eobi_employee_share],
                        ['Provident Fund', $payslip->provident_fund],
                        ['Loan',           $payslip->loan_deduction],
                        ['Absent',         $payslip->absent_deduction],
                        ['Late',           $payslip->late_deduction],
                        ['Other',          $payslip->other_deduction],
                    ] as [$lbl,$val])
                    @if($val > 0)
                    <div class="ps-row">
                        <span class="ps-row-label">{{ $lbl }}</span>
                        <span class="ps-row-val" style="color:#dc2626">{{ number_format($val) }}</span>
                    </div>
                    @endif
                    @endforeach
                    <div class="ps-total-row">
                        <span class="ps-total-label">Total Deductions</span>
                        <span class="ps-total-val" style="color:#dc2626">{{ number_format($payslip->total_deductions) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Net --}}
        <div class="ps-net">
            <div>
                <div class="ps-net-label">Net Salary Payable</div>
                @if($payslip->paid_on)<div style="font-size:11px;color:#94a3b8">Paid on {{ \Carbon\Carbon::parse($payslip->paid_on)->format('d M Y') }}</div>@endif
            </div>
            <div class="ps-net-val">PKR {{ number_format($payslip->net_salary) }}</div>
        </div>

        <div style="margin-top:1.5rem;font-size:11px;color:#94a3b8;text-align:center;border-top:1px solid #f1f5f9;padding-top:1rem">
            This is a system-generated payslip and does not require a signature. &bull; {{ $payslip->payslip_number }}
        </div>
    </div>
</div>
@endsection
