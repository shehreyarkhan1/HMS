@extends('layouts.master')
@section('title', 'Process Payroll')
@section('page-title', 'Process Payroll')
@section('breadcrumb', 'HR / Payroll / Create')

@push('styles')
    <style>
        .form-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 1rem
        }

        .form-card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .form-card-body {
            padding: 1.5rem
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: .4rem
        }

        table th {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: .65rem 1rem !important
        }

        table td {
            font-size: 13px;
            padding: .65rem 1rem !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f8fafc !important
        }

        .no-struct {
            font-size: 11px;
            color: #ef4444
        }
    </style>
@endpush

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="mb-0 fw-semibold" style="color:#1e293b">Process Payroll</h5>
            <p class="mb-0" style="font-size:13px;color:#94a3b8">Generate payslips for all active employees</p>
        </div>
        <a href="{{ route('hr.payroll.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>

    <form action="{{ route('hr.payroll.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-12 col-lg-4">
                <div class="form-card">
                    <div class="form-card-header">
                        <div
                            style="width:36px;height:36px;border-radius:9px;background:#eff6ff;color:#1d4ed8;display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div>
                            <div style="font-size:14px;font-weight:600;color:#1e293b">Payroll Period</div>
                        </div>
                    </div>
                    <div class="form-card-body">
                        <div class="mb-3">
                            <label class="form-label">Month <span class="text-danger">*</span></label>
                            <select name="month" class="form-select" required>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Year <span class="text-danger">*</span></label>
                            <input type="number" name="year" class="form-control" value="{{ $year }}"
                                min="2020" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control"
                                value="{{ today()->endOfMonth()->toDateString() }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes…"></textarea>
                        </div>
                        <div
                            style="background:#fef3c7;border-radius:10px;padding:.85rem;border:1px solid #fde68a;font-size:12px;color:#b45309;margin-bottom:1rem">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            This will generate payslips for <strong>all active employees</strong> with salary structures.
                        </div>
                        <button type="submit" class="btn btn-primary w-100"
                            onclick="return confirm('Process payroll for this month? This cannot be undone.')">
                            <i class="bi bi-play-fill me-1"></i> Generate Payroll
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="form-card">
                    <div class="form-card-header">
                        <div
                            style="width:36px;height:36px;border-radius:9px;background:#f0fdf4;color:#16a34a;display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <div style="font-size:14px;font-weight:600;color:#1e293b">Active Employees</div>
                            <div style="font-size:12px;color:#94a3b8">{{ $employees->count() }} employees will be processed
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th class="text-end">Gross</th>
                                    <th class="text-end">Net</th>
                                    <th class="text-center">Structure</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $emp)
                                    @php $s = $emp->salaryStructure; @endphp
                                    <tr>
                                        <td>
                                            <div style="font-weight:600">{{ $emp->first_name }} {{ $emp->last_name }}</div>
                                            <div style="font-size:11px;color:#94a3b8">{{ $emp->employee_id }}</div>
                                        </td>
                                        <td style="color:#64748b;font-size:12px">{{ $emp->department }}</td>
                                        <td class="text-end">
                                            @if ($s)
                                                {{ number_format($s->gross_salary) }}
                                            @else
                                                <span class="no-struct">—</span>
                                            @endif
                                        </td>
                                        <td class="text-end" style="color:#16a34a;font-weight:600">
                                            @if ($s)
                                                {{ number_format($s->net_salary) }}
                                            @else
                                                <span class="no-struct">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($s)
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                            @else
                                                <i class="bi bi-x-circle-fill text-danger" title="Will be skipped"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
